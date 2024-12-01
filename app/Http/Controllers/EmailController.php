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

    if ($emailSenders->isEmpty()) {
        return redirect()->back()->with('error', 'Nenhuma configuração de e-mail disponível.');
    }

    $errors = []; // Armazena erros para exibição posterior

    foreach ($emailSenders as $senderConfig) {
        // Criar um transporte SMTP personalizado
        $transport = new \Swift_SmtpTransport($senderConfig->smtp_host, $senderConfig->smtp_port);
        $transport->setUsername($senderConfig->smtp_user);
        $transport->setPassword($senderConfig->smtp_password);
        $transport->setEncryption($senderConfig->smtp_encryption);

        // Criar um mailer com o transporte personalizado
        $mailer = new \Swift_Mailer($transport);

        try {

            // Criar a mensagem
            $message = (new \Swift_Message('Denúncia de Website - ' . $request->domain))
                ->setFrom([$senderConfig->smtp_user => $senderConfig->smtp_user])
                ->setTo($destinatario)
                ->setBody($emailBody);

            // Enviar o e-mail
            $mailer->send($message);

            Log::info("E-mail enviado com sucesso para {$destinatario} a partir de {$senderConfig->smtp_user}");
        } catch (\Exception $e) {
            $errorMessage = "Erro ao enviar e-mail usando {$senderConfig->smtp_user}: " . $e->getMessage();
            $errors[] = $errorMessage;
        }
    }

    if (!empty($errors)) {
        return redirect()->back()->with('error', 'Alguns e-mails não foram enviados: ' . implode(', ', $errors));
    }

    return redirect()->back()->with('success', 'Denúncia enviada com sucesso.');
}



    
}
