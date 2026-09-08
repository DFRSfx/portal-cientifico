<?php

namespace App\Http\Controllers\ApisAvaliator;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApisAvaliator\CurlRequest;

class gereciadorApisController extends Controller
{
    public $count = 0;

    private $start = 0;

    private $apis = ["scopus"];

    // Api onde o pedido está a ser efetuado
    private $apiAtual;

    // Objeto que vai interagir com o json
    private $avaliador;

    public function __construct()
    {
        $this->avaliador = new AvaliatorController();
    }

    /*
    * Realiza o pedido as apis na tentativa de achar a api mais completa se o titulo não tiver sido lido anteriormente
    */
    public function obterDadosPublicacao(Request $request)
    {

        //10.1007/978-981-16-9268-0_28

        $titulo = $request->input("doi");

        if (empty($titulo) || $titulo == "N/A") return 0;

        // Percorre as APIS conhecidas e realiza um pedido a cada uma
        foreach ($this->apis as $nomeApi)
        {
            // Realiza o pedido a api
            $resposta = $this->pedidoApi($nomeApi, $titulo);

            // Se o pedido teve sucesso
            if ($resposta != 0) {
                $this->enviarAoAvaliador($resposta, $nomeApi);
            } //else

        } //foreach

        return response()->json(["data" => $this->avaliador->getResults()]);
    } 

    /**
     * Inidica que api está a sofrer um pedido 
     *@param string $api 
     */
    public function setApiAtual($api)
    {
        $this->apiAtual = $api;
    } //setApiAtual

    /**
     * Relaiza pedidos às diversas APIS
     *@param string $nomeApi
     *@param string $titulo
     */
    private function pedidoApi($nomeApi, $titulo)
    {

        // Define como o pedido deve ser realizado a cada API
        switch ($nomeApi) {
            case "scopus":

                $pedidoScopus = new CurlRequest();

                $resposta = $pedidoScopus->scopusTest($titulo);

                break;
            case "ieee":



                break;
            case "crossref":
                break;
        } //switch


        return $resposta;
    } //pedidoApi

    public function enviarAoAvaliador($retorno, $api)
    {

        // Define como o pedido deve ser realizado a cada API
        switch ($api) {
            case "scopus":

                $url = (isset($retorno["prism:url"])) ? $retorno["prism:url"] : "";

                foreach($retorno["link"] as $links)
                {
                    if($links["@ref"] == "scopus")
                    {
                        $url = $links["@href"];
                        break;
                    }
                }

                $dcIdentifier = (isset($retorno["dc:identifier"])) ? $retorno["dc:identifier"] : "";
                $eid = (isset($retorno["eid"])) ? $retorno["eid"] : "";
                $dcTitle = (isset($retorno["dc:title"])) ? $retorno["dc:title"] : "";
                $prismPublicationName = (isset($retorno["prism:publicationName"])) ? $retorno["prism:publicationName"] : "";
                $doi = (isset($retorno["prism:doi"])) ? $retorno["prism:doi"] : "";
                $dcCreator = (isset($retorno["dc:creator"])) ? $retorno["dc:creator"] : "";

                break;
            case "ieee":

                break;
        } //switch

        $this->avaliador->adicionarAvaliacao($api, $doi, $url, $dcIdentifier, $eid, $dcCreator, $dcTitle, $prismPublicationName);
    }
    
}
