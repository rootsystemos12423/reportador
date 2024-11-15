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

            // Tentar verificar o status do site
            try {
                $status = $this->checkSiteStatus($site->domain);
                
                if ($status === 200) {
                    $this->info("✔️ Site ({$site->domain}) está acessível! Status: 200 OK");
                } else {
                    $this->warn("⚠️ Site ({$site->domain}) retornou o status: $status");

                    $this->sendPushcutNotification($site->domain, $status);
                    $this->sendDiscordNotification($site->domain, $status);
                }
            } catch (Exception $e) {
                $this->error("❌ Erro ao verificar o site {$site->domain}: " . $e->getMessage());
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

        // Fazer a requisição HTTP para o domínio
        $response = Http::timeout(5)->get($domain);

        // Retorna o status HTTP
        return $response->status();
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
