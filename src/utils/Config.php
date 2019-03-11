<?php

namespace Odin\utils;

use Odin\utils\{Errors, Parse};

/**
 * Gerencia configurações da aplicação
 * @author Edney Mesquita
 */
class Config
{

    private static $data;

    /** Instanciação selada */
    private function __construct()
    {
    }

    /**
     * Inicializa as configurações
     * @return void
     */
    public static function init(string $projectFolder = "")
    {
        if(empty(ODIN_ROOT))
            die("Serial não informada");

        $root = ODIN_ROOT;
        $envFile = "{$root}/{$projectFolder}/config/.env";

        if(file_exists($envFile)){
            self::$data = Parse::env($envFile, true);
            self::exportEnv();
        }else{
            Errors::throwError("Arquivo não encontrado!", "Arquivo de configurações .env não encontrado.", "bug");
            die();
        }
    }

    /**
     * Retorna o valor de determinada configuração
     * @param string $property Propriedade a ser lida
     * @throws InvalidArgumentException
     * @return mixed
     */
    public static function get(string $property)
    {
        if (property_exists(self::$data, $property)) {
            return self::$data->$property;
        } else {
            Errors::throwError("Propriedade Indefinida!", "A propriedade chamada [{$property}] não existe.", "bug");
            die();
        }
    }

    public static function exportEnv()
    {
        foreach(self::$data as $var => $value)
        {
            define($var, $value);
        }
    }

}
