<?php

namespace Odin\database;

/**
 * @author Edney Mesquita
 */

final class SqlQuery 
{

    /**
     * Gera o SQL para o comando SELECT
     * @param string $table
     * @param array $target
     * @param string $param
     * @param int $offset
     * @param int $limit
     * @return string
     */
    public static function select(string $table, array $target, string $param = "", int $offset = 0, int $limit = 0): string 
    {
        $sql = "SELECT ";
        $properties = array();

        if (is_array($target)) {
            foreach ($target as $item) {
                $properties[] = $item;
            }
        }
        $sql .= implode(',', $properties);
        $sql .= " FROM {$table}";
        $sql .= (empty($param)) ? "" : " {$param}";
        if ($offset !== 0 && $limit !== 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }else if($offset === 0 && $limit !== 0){
            $sql .= " LIMIT {$limit}";
        }
        return $sql;
    }

    /**
     * Gera código SQL para a operação INSERT
     * @param string $table
     * @param array $data
     * @return string
     */
    public static function insert(string $table, array $data): string 
    {
        $sql = "INSERT INTO {$table} (";
        $i = 1;
        foreach ($data as $column => $value) 
        {
            if (count($data) == $i) {
                $sql .= "{$column}) VALUES (";
                break;
            }
            $sql .= "{$column}, ";
            $i++;
        }
        $i = 1;
        foreach ($data as $column => $value) 
        {
            switch (gettype($value)) 
            {
                case 'integer':
                    $value = intval($value);
                    break;
                case 'double':
                    $value = doubleval($value);
                    break;
                case 'NULL':
                    $value = 'NULL';
                    break;
                case 'string':
                    $value = "'{$value}'";
                    break;
            }
            if (count($data) == $i) {
                $sql .= "{$value})";
                break;
            }
            $sql .= "{$value}, ";
            $i++;
        }
        return $sql;
    }

    /**
     * Gera código SQL para a operação UPDATE
     * @param string $table
     * @param array $data
     * @param array $target
     * @return string
     */
    public static function update(string $table, array $data, array $target): string 
    {
        $sql = "UPDATE {$table} SET ";
        $i = 1;
        foreach ($data as $prop => $val) 
        {
            switch (gettype($val)) 
            {
                case 'integer':
                    $val = intval($val);
                    break;
                case 'double':
                    $val = doubleval($val);
                    break;
                case 'NULL':
                    $val = 'NULL';
                    break;
                case 'string':
                    $val = "'{$val}'";
                    break;
            }
            if ($i == count($data)) {
                $sql .= "{$prop} = {$val} ";
                break;
            }
            $sql .= "{$prop} = {$val}, ";
            $i++;
        }
        $target[1] = is_string($target[1]) ? "'" . strval($target[1]) . "'" : intval($target[1]);
        $sql .= "WHERE {$target[0]} = {$target[1]}";
        return $sql;
    }

    /**
     * Gera código SQL para a operação DELETE
     * @param string $table
     * @param array $target
     * @return string
     */
    public static function delete(string $table, array $target): string 
    {
        $sql = '';
        $qtd = count($target);
        if ($qtd === 1) {
            foreach ($target as $item => $val) 
            {
                $val = is_string($val) ? "'{$val}'" : intval($val);
                $sql = "DELETE FROM {$table} WHERE {$item} = {$val}";
            }
        } else {
            $sql = "DELETE FROM {$table} WHERE";
            $i = 1;
            foreach ($target as $item => $val) 
            {
                $val = is_string($val) ? "'{$val}'" : intval($val);
                if ($i == $qtd) {
                    $sql .= " {$item} = {$val};";
                    break;
                }
                $sql .= " {$item} = {$val} AND";
                $i++;
            }
        }
        return $sql;
    }
}
