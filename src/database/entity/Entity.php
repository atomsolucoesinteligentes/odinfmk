<?php

namespace Odin\database\entity;

use Odin\database\entity\IGenericTypes;
use Odin\database\entity\Mapper;
use Odin\database\Database;
use Odin\database\SqlQuery;

/**
 * Define e gerencia a estrutura das entidades
 *
 * @author Edney Mesquita
 */
abstract class Entity extends Database implements IGenericTypes
{
    /**
     * @var object $tableMap : Mapa estrutural da tabela referenciada no banco de dados
     */
    protected $tableMap;

    /**
     * @var string $tableName : Nome da tabela a ser referenciada
     */
    protected $tableName;

    /**
     * Define o mapa estrutural e o nome da tabela a ser referenciada no banco de dados
     * @param array $map : Mapeamento estrutural pré-definido da tabela
     * @return void
     */
    public function map(array $map)
    {
        $this->tableName = strtolower(@end(explode("\\", get_class($this))));
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

    /**
     * Realiza as verificações das colunas da tabela e a geração de uma estrutura de dados e a encaminha para a inserção
     * @return void
     */
    public function save()
    {
        $dataArray = [];
        foreach($this->tableMap as $key => $column)
        {
            if($column->isPK() && $column->isAutoIncrement()){
                continue;
            }else if($column->isPK() && !$column->isAutoIncrement()){
                $dataArray[$key] = $column->value;
            }else{
                if(!$column->allowNull()){
                    if(isset($column->value)){
                        $dataArray[$key] = $column->value;
                    }else{
                        die("Erro ao atribuir vazio ao campo {$key} que está definido como NOT NULL");
                    }
                }else{
                    $dataArray[$key] = isset($column->value) ? $column->value : "NULL";
                }
            }
        }
        return $this->build(SqlQuery::insert($this->tableName, $dataArray));
    }

    /**
     * Realiza a leitura e o retorno dos dados de uma entidade
     * @return void
     */
    public function read()
    {

    }

    /**
     * Realiza verificações nas propriedades da entidade e a geração de uma estrutura de dados e a encaminha para a atualização
     * @return void
     */
    public function change()
    {

    }

    /**
     * Realiza a remoção dos dados de uma determinada entidade
     * @return void
     */
    public function remove()
    {

    }

    public function build(string $sql)
    {
        echo $sql;
    }
}
