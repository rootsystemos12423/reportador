<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EmailConfig;


class EmailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_user' => 'required|email',
            'smtp_password' => 'required|string',
            'smtp_encryption' => 'required|in:tls,ssl,none',
        ]);

        // Salvar configurações no banco de dados ou no arquivo de configuração
        // Suponha que temos um modelo EmailConfig

        EmailConfig::create([
            'email' => $request->email,
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_user' => $request->smtp_user,
            'smtp_password' => $request->smtp_password,
            'smtp_encryption' => $request->smtp_encryption,
        ]);

        return back()->with('success', 'Configurações SMTP salvas com sucesso!');
    }

    public function sendDenunciaEmail(Request $request)
{
    // Validar os dados do formulário
    $request->validate([
        'domain' => 'required|string',
        'reason' => 'required|string',
        'abuse' => 'required|email',
    ]);

    // Definir o e-mail de destino (o que receberá os e-mails)
    $destinatario = $request->abuse;

    // Substituir o placeholder '{site_url}' no corpo do e-mail pelo domínio fornecido
    $emailBody = str_replace('{site_url}', $request->domain, $request->reason);

    // Obter todas as configurações de e-mails cadastrados para envio
    $emailSenders = EmailConfig::all();

    // Loop através de cada e-mail configurado como remetente
    foreach ($emailSenders as $senderConfig) {
        // Configurar o envio utilizando cada remetente (IMAP configurado)
        config([
            'mail.mailers.smtp.host' => $senderConfig->smtp_host,
            'mail.mailers.smtp.port' => $senderConfig->smtp_port,
            'mail.mailers.smtp.username' => $senderConfig->smtp_user,
            'mail.mailers.smtp.password' => $senderConfig->smtp_password,
            'mail.mailers.smtp.encryption' => $senderConfig->smtp_encryption,
            'mail.from.address' => $senderConfig->smtp_user,
        ]);

        try {
            // Enviar o e-mail usando o SMTP configurado com o corpo personalizado
            Mail::raw($emailBody, function ($message) use ($destinatario, $senderConfig, $request) {
                $message->to($destinatario)
                        ->from($senderConfig->smtp_user)
                        ->subject('Denúncia de Website - ' . $request->domain);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Erro ao enviar e-mail a partir de '{$senderConfig->smtp_user}': " . $e->getMessage());
        }
    }

    return redirect()->back()->with('success', 'Denúncia enviada com sucesso.');
}

    
}
