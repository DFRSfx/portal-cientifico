<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class StringTreatmentController extends Controller
{
    /**
     * Diferença - preg_match_all(Não para de procurar após encontrar um resultado que se adeque à expressão)
     * @param string $string
     * @param string $regexExpression
     * @param bool $matchAll
     * @return array $resultados
     */
    public static function MatchString($string, $regexExpression, $matchAll = true)
    {
        // valida qual das funções deve ser executada
        if ($matchAll) {
            preg_match_all($regexExpression, $string, $results);
        } //if
        else {
            preg_match($regexExpression, $string, $results);
        } //else

        return $results;
    }


    /**
     * explodeAndreplaceSpecificCharacters
     * Captures the keyWords from one string
     * @param string $string
     * @return array|int
     */
    public static function explodeAndReplaceSpecificCharacters($string, $charactersToRemove = array('"', ","), $substituirCharacters = array("", ";"), $characterToExplode = ";")
    {
        $treatedString = self::replaceSpecificCharacters($string, $charactersToRemove, $substituirCharacters);

        // Retira espaços à esquerda e à direita
        $treatedString = trim($treatedString);

        $array = explode($characterToExplode, $treatedString);

        return $array;
    }


    /**
     * substitui um caracter especifico de uma string, por um caracter à escolha
     * @param string $string
     * @return string 
     */
    public static function replaceSpecificCharacters($string, $caracteres, $substituicao)
    {
        // Realiza a substituição de um determinado caracter
        $string = str_replace($caracteres, $substituicao, trim($string));

        // Retorn a string
        return $string;
    }

}
