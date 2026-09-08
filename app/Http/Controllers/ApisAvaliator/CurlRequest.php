<?php

namespace App\Http\Controllers\ApisAvaliator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurlRequest extends Controller
{

    private $client;

    private $apiAddress;


    public function __construct()
    {
        $this->apiAddress = "https://qa.cienciavitae.pt/api/v1.1/";

        $curlSSLVerify = config('app.curl_ssl_verify');
        // https://api.elsevier.com/content/search/
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://api.elsevier.com', 'verify' => $curlSSLVerify, "headers" => [
            'Accept' => 'application/json',
            'X-ELS-APIKey' => '4e49d66ee022318e03b0c3e2d3e28892'
        ]]);
    }

    public function crossRef($url)
    {
    } //crossRef


    function scopusTest($doi, $start = 0, $authors = "", $scopusId = "", $titulo = "")
    {
        $stringPesquisa = "";

        /*
        if($scopusId != "")
        {
            $stringPesquisa = "AU-ID(" . $scopusId . ")";
        }//if
        else if($authors != "" && $titulo != "")
        {
            $stringPesquisa = 'TITLE("' . $titulo . '")';
        }//else
        else
        {
            $stringPesquisa = 'TITLE("' . $titulo . '")';
        }//else

        , [
            "form_params" =>[
            "start" => $start,
            "count" => "25"],
            "query" => $stringPesquisa
        ]

        */

        $stringPesquisa = rawurlencode("DOI(" . '"' . $doi . '"' . ")");

        // URL que vai ser utilizada no pedido
        $query = 'start=' . $start . '&count=25&query=' . $stringPesquisa;

        //dd($stringPesquisa);

        // $query = $stringPesquisa;

        $url = '/content/search/scopus';

        $request = $this->client->request('GET', $url, [
            "query" => $query
        ]);

        $dados = json_decode($request->getBody()->getContents(), true);

        if ($request->getStatusCode() != 200 || $dados["search-results"]["opensearch:totalResults"] == 0) {
            return 0;
        } //if
        else {
            if (!isset($dados["search-results"]["entry"])) {
                return 0;
            } //if

            // Percorre as entrys
            foreach ($dados["search-results"]["entry"] as $entry) {
                if ($entry["prism:doi"] == $doi) {
                    return $entry;
                } //if
            } //foreach

        } //else

        $start = $start + 25;

        $this->scopusTest($start, $authors, $scopusId, $titulo);



        //var_dump($dados);


        /*
    
    ["prism:url"]=>
    string(63) "https://api.elsevier.com/content/abstract/scopus_id/85127653531"
    ["dc:identifier"]=>
    string(21) "SCOPUS_ID:85127653531"
    ["eid"]=>
    string(18) "2-s2.0-85127653531"
    ["dc:title"]=>
    string(51) "Digital Marketing Strategy: A Step-By-Step Approach"
    ["dc:creator"]=>
    string(11) "Guerra M.J."
    ["prism:publicationName"]=>
    string(42) "Smart Innovation, Systems and Technologies"
    
    
    */
    } //scopusTest


    /**
     * Obtem dados da Scopus
     * Retorna um 0 no caso de falha ou um array no caso de sucesso
     * @param string $titulo
     * @return int|array Retorna 0 no caso de falha
     */
    function scopus($titulo)
    {
        // Array com os parametros que vão ser utilizados na header
        $header = [
            'Accept:application/json',
            'X-ELS-APIKey:4e49d66ee022318e03b0c3e2d3e28892'
        ];

        // URL que vai ser utilizada no pedido
        $url = 'https://api.elsevier.com/content/search/scopus?query=';

        // Inicializa a Sessão do Curl
        $ch = curl_init();

        // Parametros do CURL
        curl_setopt($ch, CURLOPT_URL, $url . 'TITLE("' . $titulo . '")');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Output
        $server_output = curl_exec($ch);

        // Código da resposta
        $httpCodigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fecha a sessão do curl
        curl_close($ch);

        // Transforma o json recebido num array
        $dados = json_decode($server_output, true);

        // Validação da resposta
        if ($httpCodigo == 200 && $dados["search-results"]["opensearch:totalResults"] != 0) {
            // Percorre as entrys
            foreach ($dados["search-results"]["entry"] as $entry) {
                if ($entry["dc:title"] == $titulo) {
                    return $entry;
                } //if
            } //foreach
        } //if
        else {
            echo "falhou!\n\n\n\n\n";
            return 0;
        } //else

    } //PedidoAPI



}
