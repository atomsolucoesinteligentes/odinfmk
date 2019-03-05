<?php

namespace Odin\database\entity;

/**
 * Gera os mapas estruturais orientados a objeto das tabelas
 *
 * @author Edney Mesquita
 */
class Mapper
{
    /**
     * Gera o objeto de mapeamento da tabela
     * @param array $mapArray : Array pré-definido passado como parâmetro para Entity::map
     * @return object
     */
    public static function generate(array $mapArray)
    {
        $mapObject = new \stdClass();
        foreach($mapArray as $key => $value)
        {
            if(is_array($value)){
                $mapObject->{$key} = self::generate($value);
            }else{
                $mapObject->{$key} = $value;
            }
        }
        return $mapObject;
    }
}
