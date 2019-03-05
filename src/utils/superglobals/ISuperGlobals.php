<?php

namespace Easy\utils\superglobals;

interface ISuperGlobals{

    public function get($name); //gets value
    public function put($name, $value); //puts a value 
    public function exists($name); //verify if exists
    public function equals($name, $value); //verify if equals
    public function all(); //gets all values
    public function forget($name); //delete a value
    public function flush(); //delete all values

}

 ?>
