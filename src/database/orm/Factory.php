<?php

namespace Freya\orm;

use Freya\orm\Connection;
use Freya\orm\QueryBuilder;
use Odin\utils\collections\Dictionary;

class Factory 
{
    protected static $connections = [];
    
    public static function get(string $name, Connection $connection = null): QueryBuilder
    {
        if(!is_null($connection)) 
            if(!isset(static::$connections[$name]))
                static::$connections[$name] = new QueryBuilder("", $connection);
        return static::$connections[$name];
    }
    
    public static function fromDictionary(Dictionary $dictionary)
    {
        foreach ($dictionary->all() as $key => $connection) {
            static::$connections[$key] = new QueryBuilder("", $connection);
        }
    }
}
