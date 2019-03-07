<?php

namespace Odin\database\entity;

use Odin\database\entity\IGenericTypes;
use Odin\database\entity\Mapper;
use Odin\database\{ORMMapper};

/**
 * Define e gerencia a estrutura das entidades
 *
 * @author Edney Mesquita
 */
abstract class Entity extends ORMMapper implements IGenericTypes
{
    /**
     * @var object $tableMap : Mapa estrutural da tabela referenciada no banco de dados
     */
    protected $tableMap;

    /**
     * @var string $tableName : Nome da tabela a ser referenciada
     */
    public $tableName;

    public function __construct()
    {
        parent::__construct();
        parent::setTableName($this->tableName);
    }

    /**
     * Define o mapa estrutural e o nome da tabela a ser referenciada no banco de dados
     * @param array $map : Mapeamento estrutural pré-definido da tabela
     * @return void
     */
    public function map(array $map)
    {
        $this->tableMap = Mapper::generate($map);
    }

    public function __get($key)
    {
        echo $key;
    }

    /**
     * Permite a alteração das propriedades da entidade caso ela exista, caso contrario a cria
     * @param string $key : Nome da propriedade a ser definida ou alterada
     * @param mixed $value : Valor a ser atribuido à propriedade
     * @return void
     */
    public function __set(string $key, $value)
    {
        if($this->tableMap->{$key}){
            $this->tableMap->{$key}->value = $value;
        }
    }
}
