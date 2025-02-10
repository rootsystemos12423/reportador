<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Campaign;
use App\Models\RequestLog;
use App\Models\UtmsRequest;
use Illuminate\Support\Facades\Http;
use Detection\MobileDetect;
use App\Models\Rule;
use Illuminate\Support\Facades\Log;

class WhiteRabbitController extends Controller
{

    public function show(){
        abort(403, 'Acesso não permitido.');
    }


    public function dashboard()
{
    // Consultar os dados de requests dos últimos 15 dias agrupados por data
    $requests = RequestLog::selectRaw('DATE(created_at) as date, count(*) as total_requests, 
            sum(CASE WHEN allowed = 0 THEN 1 ELSE 0 END) as safe_page,
            sum(CASE WHEN allowed = 1 THEN 1 ELSE 0 END) as offer_page') // Corrigido aqui
        ->where('created_at', '>=', now()->subDays(15)) // Filtra os últimos 15 dias
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // Organize os dados para passar para a view
    $dates = $requests->pluck('date')->toArray();
    $totalRequests = $requests->pluck('total_requests')->toArray();
    $safePage = $requests->pluck('safe_page')->toArray(); 
    $offerPage = $requests->pluck('offer_page')->toArray();

    return view('rabbit.index', compact('dates', 'totalRequests', 'safePage', 'offerPage'));
}



    public function domains(){

        $domains = Domain::all();

        return view('rabbit.domains', compact('domains'));
    }

    public function campaigns(){

        $campaigns = Campaign::all();

        return view('rabbit.campaigns', compact('campaigns'));
    }

    public function requests(){

        $requests = RequestLog::latest()->paginate(50);

        return view('rabbit.requests', compact('requests'));
    }

    public function campaign(){

        $domains = Domain::all();

        return view('rabbit.campaign', compact('domains'));
    }

    public function cloacker(Request $request, $id){

        $xid = $request->query('xid');
        
        $campaign = Campaign::where('hash', $id)->first();

        if(!$campaign){
            return 'NOT FOUND';
        }

        $domain = $request->getHost();
        $safePage = "https://{$domain}/{$id}/safe";

        $ip = $request->header('CF-Connecting-IP') ?? $request->ip();

        $apiUrl = "http://ip-api.com/json/{$ip}?fields=status,message,continent,continentCode,country,countryCode,region,regionName,city,zip,lat,lon,timezone,offset,currency,isp,org,as,asname,reverse,mobile,proxy,hosting,query";

        $response = Http::get($apiUrl);
        $geoData = $response->json(); // Converte a resposta para array

          // Verifica se a API retornou sucesso
          if ($geoData['status'] !== 'success') {
           return 'ERROR API FAILED';
        }

        $targetCountriesArray = json_decode($campaign->target_countries, true);

        $userLanguage = substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 5);

        $targetDevices = json_decode($campaign->target_devices, true);

        $detect = new MobileDetect();

        // Detectando o dispositivo
        $isMobile = $detect->isMobile();
        $isTablet = $detect->isTablet();
        $isDesktop = !$isMobile && !$isTablet; // Se não for mobile nem tablet, é desktop
        $isTV = false;

        $requestLog = RequestLog::create([
            'ip' => $ip,
            'continent' => $geoData['continent'] ?? null,
            'country' => $geoData['country'] ?? null,
            'country_code' => $geoData['countryCode'] ?? null,
            'timezone' => $geoData['timezone'] ?? null,
            'isp' => $geoData['isp'] ?? null,
            'org' => $geoData['org'] ?? null,
            'asn' => $geoData['as'] ?? null,
            'reverse_dns' => $geoData['reverse'] ?? null,
            'language' => $userLanguage,
            'device' => $isDesktop ? 'PC' : ($isMobile ? 'TEL' : ($isTablet ? 'TAB' : ($isTV ? 'TV' : 'UNKNOWN'))),
            'user_agent' => $request->userAgent(),
            'allowed' => null,
            'reason' => null,
            'campaign_id' => $campaign->id,
        ]);

        // ✅ Captura todas as UTMs da URL e salva na tabela `utms_request`
        $utmParams = [
            'cwr', 'twr', 'gwr', 'domain', 'cr', 'plc', 'mtx', 'rdn', 'kw', 
            'cpc', 'disp', 'int', 'loc', 'net', 'pos', 'dev', 'gclid', 'wbraid', 'gbraid', 'xid'
        ];
        
        $utmData = [];
        foreach ($utmParams as $param) {
            $utmData[$param] = $request->query($param);
        }

        $utm = UtmsRequest::create(array_merge($utmData, [
            'request_id' => $requestLog->id,
            'ref_id' => $request->query('gclid'),
        ]));


        if ($detect->is('TV')) {
            $isTV = true;
        }

        if (!$request->has('xid')) {
            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Missing xId'
            ]);
        
            return redirect()->to($safePage);
        }

        if ($xid !== $campaign->xid) {
            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Xid Invalid'
            ]);
        
            return redirect()->to($safePage);
        }

        if($request->query('gclid') === null){

            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Gclid Null'
            ]);
        

            return redirect()->to($safePage);
        }

        if ($campaign->traffic_source === 'G-SEARCH' && $request->query('net') !== 'g') {
            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Network Not Match With Campaign Type'
            ]);
        
            return redirect()->to($safePage);
        }
        
        if($this->checkGoogleBot($ip, $geoData) === true){

            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Google Bot Detected'
            ]);
        

            return redirect()->to($safePage);
        }

        $rule = $this->checkRule($geoData, $request->userAgent(), $ip);

        if ($rule !== null) {
            // Se uma regra foi atendida, atualiza o log com o motivo do bloqueio
            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Bloqueado por Regra Privada (Condition Type da Regra: ' . $rule->condition_type . ')'
            ]);

            return redirect()->to($safePage);
        }

        if (!in_array($geoData['countryCode'], $targetCountriesArray)) {

            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Country Not Allowed'
            ]);

            return redirect()->to($safePage);
        }

        if ($campaign->language !== $userLanguage) {

            RequestLog::where('ip', $ip)->latest()->first()->update([
                'allowed' => false,
                'reason' => 'Language Not Allowed'
            ]);

            return redirect()->to($safePage);
        }

        if (($isDesktop && !in_array('PC', $targetDevices)) ||
            ($isMobile && !in_array('TEL', $targetDevices)) ||
            ($isTablet && !in_array('TAB', $targetDevices)) ||
            ($isTV && !in_array('TV', $targetDevices))) {

                RequestLog::where('ip', $ip)->latest()->first()->update([
                    'allowed' => false,
                    'reason' => 'Device Not Allowed'
                ]);

                return redirect()->to($safePage);

        }

        RequestLog::where('ip', $ip)->latest()->first()->update([
            'allowed' => true,
            'reason' => 'Acess Granted'
        ]);

        $offerPages = json_decode($campaign->offer_pages, true);

        // Verifica se existe pelo menos uma página de oferta
        if (!empty($offerPages)) {
            // Define os parâmetros dinâmicos
            $queryParams = [
                'gad_source' => '1',
                'gclid' => request('gclid'),
                'ref_id' => request('ref_id'),
                'wbraid' => request('wbraid'),
                'gbraid' => request('gbraid'),
            ];

            // Remove os parâmetros nulos ou vazios
            $queryParams = array_filter($queryParams);

            // Monta a URL final
            $unsafePage = $offerPages[0] . '?' . http_build_query($queryParams);
        }


        // Verifica se há pelo menos um link válido no array
        if (!empty($offerPages) && is_array($offerPages)) {
            return redirect()->to($unsafePage); // Redireciona para o primeiro link
        }

        // Retorna os dados obtidos para debug
        abort(403, 'WORKING');
    }

    private function checkGoogleBot($ip, $geoData)
{
    // Verifica o reverse DNS do IP
    $reverseDns = $geoData['reverse'];

    // Padrões para checar o reverse DNS
    $googleBotPatterns = [
        '/^crawl-\d{1,3}-\d{1,3}-\d{1,3}-\d{1,3}\.googlebot\.com$/i',
        '/^geo-crawl-\d{1,3}-\d{1,3}-\d{1,3}-\d{1,3}\.geo\.googlebot\.com$/i',
        '/^rate-limited-proxy-\d{1,3}-\d{1,3}-\d{1,3}-\d{1,3}\.google\.com$/i',
        '/^\d{1,3}-\d{1,3}-\d{1,3}-\d{1,3}\.gae\.googleusercontent\.com$/i',
        '/^google-proxy-\d{1,3}-\d{1,3}-\d{1,3}-\d{1,3}\.google\.com$/i',
    ];

    // Verifica se o reverse DNS corresponde a algum dos padrões de GoogleBot
    foreach ($googleBotPatterns as $pattern) {
        if (preg_match($pattern, $reverseDns)) {
            return true; // Se for um bot do Google, retorna verdadeiro
        }
    }

    // Verifica outros indicadores como ISP, organização e AS
    if (isset($geoData['isp']) && strpos($geoData['isp'], 'Google') !== false) {
        return true; // O ISP é Google
    }

    if (isset($geoData['org']) && strpos($geoData['org'], 'Google') !== false) {
        return true; // A organização é Google
    }

    if (isset($geoData['asname']) && strpos($geoData['asname'], 'GOOGLE') !== false) {
        return true; // O ASname contém "GOOGLE"
    }

    // Caso contrário, retorna falso
    return false;
}

private function checkRule($data, $userAgent, $ip)
{
    $rules = Rule::all();

    foreach ($rules as $rule) {
        $values = $this->decodeValues($rule->values);

        switch ($rule->condition_type) {
            case 'isp':
                if (isset($data['isp']) && $this->checkCondition($data['isp'], $rule->condition_operator, $values)) {
                    return $rule;
                }
                break;

            case 'ip':
                if (isset($ip) && $this->checkCondition($ip, $rule->condition_operator, $values)) {
                    return $rule;
                }
                break;

            case 'asn':
                if (isset($data['asname']) && $this->checkCondition($data['asname'], $rule->condition_operator, $values)) {
                    return $rule;
                }
                break;

            case 'user-agent':
                if (isset($userAgent) && $this->checkCondition($userAgent, $rule->condition_operator, $values)) {
                    return $rule;
                }
                break;

            case 'reverse-dns':
                if (isset($data['reverse']) && $this->checkCondition($data['reverse'], $rule->condition_operator, $values)) {
                    return $rule;
                }
                break;

            default:
                break;
        }
    }

    return null;
}

// Função auxiliar para decodificar valores, caso seja uma string JSON
private function decodeValues($values)
{
    // Se for uma string, tenta decodificar
    if (is_string($values)) {
        $decodedValues = json_decode($values, true); // Decodifica se for JSON
        return is_array($decodedValues) ? $decodedValues : [$values]; // Retorna um array se decodificado corretamente, senão retorna a string original em um array
    }

    // Se já for um array, retorna como está
    return $values;
}

// Função auxiliar para checar a condição (equal, contains, etc.)
private function checkCondition($value, $operator, $ruleValues)
{
    foreach ($ruleValues as $ruleValue) {
        switch ($operator) {
            case 'equal':
                if ($value === $ruleValue) {
                    return true;
                }
                break;

            case 'contains':
                if (strpos($value, $ruleValue) !== false) {
                    return true;
                }
                break;

            case 'different':
                if ($value !== $ruleValue) {
                    return true;
                }
                break;

            default:
                return false;
        }
    }

    return false;
}



public function safePage(Request $request, $id)
{
    // Obter o domínio da requisição
    $domain = $request->getHost();

    // Procurar a campanha associada ao hash
    $campaign = Campaign::where('hash', $id)->first();

    if (!$campaign) {
        abort(404, 'CAMPAIGN NOT FOUND');
    }

    // Caminho do arquivo da Safe Page dentro do storage
    $filePath = storage_path('app/public/' . $campaign->safe_page);

    if (!file_exists($filePath)) {
        abort(404, 'SAFE PAGE NOT FOUND');
    }

    // Ler o conteúdo do arquivo Safe Page
    $content = file_get_contents($filePath);

    // Base URL para os recursos estáticos dentro do diretório
    $baseUrl =  'https://'.$domain.'/storage/' . dirname($campaign->safe_page);

    // Ajustar caminhos relativos dos recursos (src, href, link, script)
    $content = preg_replace(
        '/(src|href|link|script)=["\'](?!http|https|\/\/)([^"\']+)["\']/i',
        '$1="' . $baseUrl . '/$2"',
        $content
    );

    return response($content, 200)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('Content-Disposition', 'inline; filename="index.html"');
}


public function RequestShow($id){

    $request = RequestLog::findOrfail($id);

    return view('rabbit.request', compact('request'));
}

public function rules(){

    $rules = Rule::paginate(10); 

    return view('rabbit.rules', compact('rules'));
}

public function storeRule(Request $request)
{
    try {
        // Validação dos campos
        $request->validate([
            'action' => 'required|in:block,allow',
            'condition_type' => 'required|in:isp,ip,asn,user-agent,reverse-dns', // Adicionado reverse-dns
            'condition_operator' => 'required|in:equal,contains,different',
            'values' => 'required|json',
        ]);

        // Decodificar o campo values
        $values = json_decode($request->input('values'), true);

        // Verificar se a decodificação foi bem-sucedida e se $values é um array
        if (is_array($values)) {
            // Criar uma regra para cada valor no array values
            foreach ($values as $value) {
                Rule::create([
                    'action' => $request->input('action'),
                    'condition_type' => $request->input('condition_type'),
                    'condition_operator' => $request->input('condition_operator'),
                    'values' => json_encode([$value]), // Armazenar apenas o valor atual no JSON
                ]);
            }

            // Retornar sucesso
            return redirect()->back()->with('success', 'Regras adicionadas com sucesso!');
        } else {
            return redirect()->back()->with('error', 'O campo "values" não contém um JSON válido.');
        }
    } catch (\Exception $e) {
        // Log de erro
        Log::error('Erro ao criar a regra de bloqueio.', [
            'user_id' => auth()->user()->id ?? 'guest',
            'error_message' => $e->getMessage(),
            'request_data' => $request->all(),
        ]);

        // Retornar erro
        return redirect()->back()->with('error', 'Ocorreu um erro ao adicionar a regra.');
    }
}





}
