<?php

namespace App\Http\Controllers\ApisAvaliator;

use Illuminate\Http\Request;
use Spatie\FlareClient\Api;

class Publicacao
{
    private $pontuacao;

    public function __set($name, $value)
    {

        $this->$name = $value;

        // valida se o valor recebido é diferente de vazio
        if ($value && $name != "api") {
            // Atribui pontuação
            $this->pontuacao++;
        } //if

    } //__set

    public function getPontuacao()
    {
        return $this->pontuacao;
    } //getPontuacao
}
