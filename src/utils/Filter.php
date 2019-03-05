<?php

namespace Odin\utils;

/**
 * Trata entradas de texto
 * @author Edney Mesquita
 */
class Filter
{

    /**
     * Valida o tipo do arquivo repassado
     * @param string $file Arquivo a ser verificado
     * @param string $type Type a ser comparado
     * @return boolean
     */
    public static function validFile(string $file, string $type)
    {
        $mime = mime_content_type($file);
        $mime = explode("/", $mime);
        if (count($mime) > 1) {
            if (preg_match("/{$type}/i", $mime[1])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Remove os principais caracteres nocivos
     * @param string $input Texto a ser avaliado
     * @return mixed Texto processado
     */
    public static function clear($input)
    {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    /**
     * Limpa/valida string de e-mail
     * @param string $email E-mail a ser validado
     * @return string E-mail limpo
     */
    public static function email($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public static function encoded($input)
    {
        return filter_var($input, FILTER_SANITIZE_ENCODED);
    }

    /**
     * Verifica tamanho da string
     * @param string $input Texto em questão
     * @param int $limit Limite de caracteres
     * @return string String verificada
     */
    public static function length($input, int $limit)
    {
        if (strlen($input) > $limit) {
            return substr($input, 0, $limit);
        }
        return $input;
    }

    /**
     * Verifica se um email é válido
     * @param string $email
     * @return bool
     */
    public static function validEmail(string $email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Verifica se o CPF é válido
     * @param string $cpf
     * @return boolean
     */
    public static function validCPF(string $cpf)
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++)
        {
            for ($d = 0, $c = 0; $c < $t; $c++)
            {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }
}
