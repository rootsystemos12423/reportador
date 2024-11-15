<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use Illuminate\Support\Facades\Http;
use Exception;

class SiteChecker extends Command
{
    protected $signature = 'site:check';
    protected $description = 'Verifica o status HTTP de sites cadastrados';

    public function handle()
    {
        // Obtém todos os sites cadastrados
        $sites = Site::all();

        if ($sites->isEmpty()) {
            $this->info('Nenhum site cadastrado.');
            return;
        }

        foreach ($sites as $site) {
            $this->info("Verificando o domínio: {$site->domain}");
        
            try {
                $status = $this->checkSiteStatus($site->domain);
        
                if ($status === 200) {
                    $this->info("✔️ Site ({$site->domain}) está acessível! Status: 200 OK");
                } elseif ($status === 0) {
                    $this->warn("⚠️ Site ({$site->domain}) está offline ou tem problema de SSL!");
        
                    // Enviar notificações quando o site está offline ou com erro SSL
                    $this->sendPushcutNotification($site->domain, $status);
                    $this->sendDiscordNotification($site->domain, $status);
                } else {
                    $this->warn("⚠️ Site ({$site->domain}) retornou o status: $status");
        
                    // Enviar notificações para outros status não 200
                    $this->sendPushcutNotification($site->domain, $status);
                    $this->sendDiscordNotification($site->domain, $status);
                }
            } catch (Exception $e) {
                $this->error("❌ Erro inesperado ao verificar o site {$site->domain}: " . $e->getMessage());
        
                // Notificar sobre o erro
                $this->sendPushcutNotification($site->domain, 'Erro');
                $this->sendDiscordNotification($site->domain, 'Erro');
            }
        }
    }

    /**
     * Função para verificar o status HTTP do site.
     */
    private function checkSiteStatus($domain)
{
    // Adiciona 'http://' caso não tenha sido informado
    if (!str_starts_with($domain, 'http://') && !str_starts_with($domain, 'https://')) {
        $domain = 'http://' . $domain;
    }

    try {
        // Fazer a requisição HTTP para o domínio
        $response = Http::timeout(5)->get($domain);

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            return $response->status();
        } else {
            // Se a resposta não for 2xx, retornar o código de status
            return $response->status();
        }

    } catch (\Illuminate\Http\Client\RequestException $e) {
        // Verificar se o erro é relacionado ao certificado SSL
        if (str_contains($e->getMessage(), 'cURL error 60')) {
            $this->error("❌ Problema de certificado SSL para o site {$domain}: " . $e->getMessage());
        } elseif (str_contains($e->getMessage(), 'cURL error')) {
            // Erro genérico de cURL (rede, DNS, timeout, etc)
            $this->error("❌ Erro ao verificar o site {$domain} (erro de rede): " . $e->getMessage());
        } else {
            $this->error("❌ Erro inesperado ao verificar o site {$domain}: " . $e->getMessage());
        }

        // Retorna o status 0 em caso de erro de rede ou SSL, indicando que o site está offline
        return 0;
    }
}


    private function sendPushcutNotification($domain, $status)
    {
        $webhooks = \App\Models\PushcutWebhook::all();
    
        if ($webhooks->isEmpty()) {
            $this->warn("Nenhum webhook Pushcut ativo encontrado.");
            return;
        }
    
        $notificationTitle = "Site Offline";
        $notificationMessage = "O site {$domain} retornou o status HTTP {$status}";
    
        foreach ($webhooks as $webhook) {
            $response = Http::post($webhook->url, [
                'title' => $notificationTitle,
                'message' => $notificationMessage,
            ]);
    
            if ($response->successful()) {
                $this->info("🔔 Notificação enviada via Pushcut para {$domain} usando {$webhook->name}!");
            } else {
                $this->error("❌ Falha ao enviar notificação via Pushcut para {$webhook->name}: " . $response->body());
            }
        }
    }

    private function sendDiscordNotification($domain, $status)
    {
        $discordWebhookUrl = env('DISCORD_WEBHOOK');
        $message = "**⚠️ Alerta de Site Offline**\n\n" .
                   "🔗 **Domínio**: {$domain}\n" .
                   "❗ **Status HTTP**: {$status}\n" .
                   "⏰ **Data/Hora**: " . now()->format('d/m/Y H:i:s');

        if (!$discordWebhookUrl) {
            $this->error("Webhook do Discord não configurado.");
            return;
        }

        $response = Http::post($discordWebhookUrl, [
            'content' => $message,
        ]);

        if ($response->successful()) {
            $this->info("🔔 Notificação enviada para o Discord para {$domain}!");
        } else {
            $this->error("❌ Falha ao enviar notificação para o Discord: " . $response->body());
        }
    }
}
