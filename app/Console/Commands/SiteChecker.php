<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use Illuminate\Support\Facades\Http;
use Exception;
use App\Models\BackupLink;
use App\Models\Campaign;

class SiteChecker extends Command
{
    protected $signature = 'site:check';
    protected $description = 'Verifica o status HTTP de sites cadastrados';

    public function handle()
    {
        $campaigns = Campaign::all();

        foreach ($campaigns as $campaign) {
            $offerPages = json_decode($campaign->offer_pages, true); // Converte a string JSON para array
        
            if (is_array($offerPages) && !empty($offerPages)) { // Verifica se é um array e não está vazio
                foreach ($offerPages as $offerPage) {
                    $this->info("Verificando o domínio: {$offerPage}");
        
                    try {
                        // Verifica o status do site
                        $status = $this->checkSiteStatus($offerPage);
        
                        if ($status === 200) {
                            $this->info("✔️ Site ({$offerPage}) está acessível! Status: 200 OK");
                        } elseif ($status === 404) {
                            // Se o status for 404, deletar o link
                            $this->error("❌ Site ({$offerPage}) não encontrado. Deletando...");
                        
                            // Decodificar a string JSON para um array
                            $offerPages = json_decode($campaign->offer_pages, true);
                        
                            // Verificar se é um array e contém o link
                            if (is_array($offerPages) && in_array($offerPage, $offerPages)) {
                                // Remover o link específico do array
                                $offerPages = array_filter($offerPages, function ($url) use ($offerPage) {
                                    return $url !== $offerPage; // Filtra o link que precisa ser removido
                                });
                        
                                // Reindexar o array para evitar índices quebrados após o filtro
                                $offerPages = array_values($offerPages);
                        
                                // Atualizar o campo 'offer_pages' com o novo array, convertido de volta para JSON
                                $campaign->offer_pages = json_encode($offerPages);
                                $campaign->save(); // Salva as mudanças no banco
                        
                                // Enviar notificação para o Discord
                                $discordWebhookUrl = env('DISCORD_WEBHOOK');
                                $message = "**⚠️⚠️⚠️ ALERTA BACKUP REMOVIDO SHOPIFY OFF ⚠️⚠️⚠️**\n\n" .
                                        "🔗 **Domínio**: {$offerPage}\n" .
                                        "❗ **Status HTTP**: {$status}\n" .
                                        "⏰ **Data/Hora**: " . now()->format('d/m/Y H:i:s');
                        
                                if (!$discordWebhookUrl) {
                                    $this->error("Webhook do Discord não configurado.");
                                    return;
                                }
                        
                                // Enviar notificação para o Discord
                                $response = Http::post($discordWebhookUrl, [
                                    'content' => $message,
                                ]);
                            } else {
                                $this->warn("❌ O domínio {$offerPage} não foi encontrado no array de 'offer_pages'.");
                            }
                        } elseif ($status === 403) {
                            $this->warn("⚠️ Site ({$offerPage}) retornou o status 403 - Acesso proibido.");
                            // Aqui você pode adicionar ações específicas para o status 403
                        } else {
                            $this->warn("⚠️ Site ({$offerPage}) retornou o status: $status");
        
                            // Enviar notificações para outros status não 200
                            $this->sendPushcutNotification($offerPage, $status);
                            $this->sendDiscordNotification($offerPage, $status);
                        }
                    } catch (Exception $e) {
                        $this->error("❌ Erro inesperado ao verificar o site {$offerPage}: " . $e->getMessage());
        
                        // Notificar sobre o erro
                        $this->sendPushcutNotification($offerPage, 'Erro');
                        $this->sendDiscordNotification($offerPage, 'Erro');
                    }
        
                    sleep(1); // Pausa de 1 segundo entre as verificações
                }
            } else {
                $this->warn("A campanha não possui links de oferta válidos ou a conversão do JSON falhou.");
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
        $response = Http::timeout(10)->get($domain);

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
