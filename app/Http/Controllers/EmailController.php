<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EmailConfig;
use Resend\Laravel\Facades\Resend;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        // Salvar configurações no banco de dados ou no arquivo de configuração
        // Suponha que temos um modelo EmailConfig

        EmailConfig::create([
            'email' => $request->email,
            'smtp_host' => 'smtp.resend.com',
            'smtp_port' => '465',
            'smtp_user' => 'resend',
            'smtp_password' => env('RESEND_API_KEY'),
            'smtp_encryption' => 'SSL',
        ]);

        return back()->with('success', 'Configurações SMTP salvas com sucesso!');
    }

    public function sendDenunciaEmail(Request $request)
    {
    
        // Definir o e-mail de destino (o que receberá os e-mails)
        $destinatario = $request->abuse;
    
        // Substituir o placeholder '{site_url}' no corpo do e-mail pelo domínio fornecido
        $emailBody = str_replace('{site_url}', $request->domain, $request->reason);
        $emailBody = str_replace('{brand}', $request->brand, $emailBody);
        
        // Obter todas as configurações de e-mails cadastrados para envio
        $emailSenders = EmailConfig::all();
    
        if ($emailSenders->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhuma configuração de e-mail disponível.');
        }
    
        $errors = []; // Armazena erros para exibição posterior
    
        foreach ($emailSenders as $senderConfig) {
    
            try {
                Resend::emails()->send([
                    'from' => 'Diego Daminelli Lopes <' . $senderConfig->email . '>',
                    'to' => [$destinatario],
                    'subject' => 'Trademarked Symbol: '.$request->brand.' [HIGH IMPORTANCE]',
                    'html' => $emailBody,
                ]);
    
        
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
