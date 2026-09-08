<?php

namespace App\Http\Controllers\ApisAvaliator;

use Illuminate\Http\Request;

use App\Http\Controllers\ApisAvaliator\Publicacao;


/*

 id do pacote pacote 3


 0 => pacote 1 => idPacote

 1 => pacote 2 => idPacote

 2 => pacote 3 => idPacote

 idPacote => pacote 1 => idPacote, nome, data
idPacote => pacote 2 => idPacote, nome, data
idPacote => pacote 3 => idPacote, nome, data


sem id

sem id


*/

class AvaliatorController extends Publicacao
{
    public static $count = 0;

    private $avaliacoes = [];

    private $pontuacao;

    private $api;

    private $FoundPubs = [];

    public function adicionarAvaliacao($api, $doi, $url, $dcIdentifier, $eid, $dcCreator, $dcTitle, $prismPublicationName)
    {
        $publicacao = new parent();

        $publicacao->api = $api;
        $publicacao->doi = $doi;
        $publicacao->url = $url;
        $publicacao->dcIdentifier = $dcIdentifier;
        $publicacao->eid = $eid;
        $publicacao->dcCreator = $dcCreator;
        $publicacao->dcTitle = trim($dcTitle);
        $publicacao->prismPublicationName = $prismPublicationName;

        $array = ["doi" => $doi, "dcTitle" => $dcTitle];

        $array = array_filter($array);

        $posicaoPublicacao = $this->findPublicacao($array);

        if ($posicaoPublicacao == -1)
        {
            // Adiciona o elemento
            array_push($this->avaliacoes, $publicacao);

            // Adiciona o título aos títulos já lidos
            array_push($this->FoundPubs, $publicacao["dcTitle"]);
        } //if
        else 
        {
            // Junta os dados
            $this->innerJoin($posicaoPublicacao, $publicacao);
        } //else

    } //adicionarAvaliacao

    /**
     * Retorna a melhor avaliação
     * @return 
     */
    public function melhorAvaliacao()
    {
        $listaAvaliacoes = $this->avaliacoes;

        // Valida se a lista está vazia
        if (!$listaAvaliacoes) {
            return 0;
        } //if

        // Declaração / Inicialização de Variáveis
        $max = 0;
        $index = 0;

        // Percorre e enontra a pontuação máxima
        for ($i = 0; $i < count($listaAvaliacoes); $i++) {
            $pontuacao = $listaAvaliacoes[$i]->getPontuacao();

            if ($pontuacao > $max) {
                $max = $pontuacao;
                $index = $i;
            } //if
        } //for

        echo "Os pedidos foram Avaliados com sucesso! : " . self::$count;

        // Retorna o objeto que tem maior pontuação
        return $listaAvaliacoes[$index];
    } //melhorAvaliacao

    public function findPublicacao($AtributosPesquisar)
    {
        $resposta = -1;

        if (!$this->avaliacoes) return $resposta;

        foreach ($AtributosPesquisar as $key => $value) 
        {
            $count = count($this->avaliacoes);

            // finds the publication
            for ($i = 0; $i < $count; $i++) 
            {
                if ($this->avaliacoes[$i]->{$key} == $value) 
                {
                    $resposta = $i;
                    break;
                }
            }
            
        } //foreach

        return $resposta;
    } //findPublicacao


    public function innerJoin($posicaoArray, $elementoJoin)
    {

        $elemento = $this->avaliacoes[$posicaoArray];

        $apis = $elemento->api . "," . $elementoJoin->api;

        // Realiza o join
        $elemento->api = $apis;
        $elemento->doi = (!$elementoJoin->doi) ? $elemento->doi : $elementoJoin->doi;
        $elemento->url = (!$elementoJoin->url) ? $elemento->url : $elementoJoin->url;
        $elemento->dcIdentifier = (!$elementoJoin->dcIdentifier) ? $elemento->dcIdentifier : $elementoJoin->dcIdentifier;
        $elemento->eid = (!$elementoJoin->eid) ? $elemento->eid : $elementoJoin->eid;
        $elemento->dcCreator = (!$elementoJoin->dcCreator) ? $elemento->dcCreator : $elementoJoin->dcCreator;
        $elemento->dcTitle = (!$elementoJoin->dcTitle) ? $elemento->dcTitle : $elementoJoin->dcTitle;
        $elemento->prismPublicationName = (!$elementoJoin->prismPublicationName) ? $elemento->prismPublicationName : $elementoJoin->prismPublicationName;

        $this->avaliacoes[$posicaoArray] = $elemento;
    } //innerJoin

    /**
     * Limpa a lista de avaliacoes
     */
    public function limpar()
    {
        $this->avaliacoes = [];
    } //limpar

    public function getPontuacao()
    {
        return $this->pontuacao;
    } //getPontuacao

    public function getApi()
    {
        return $this->api;
    } //getApi

    public function getResults()
    {
        return $this->avaliacoes;
    }
}
