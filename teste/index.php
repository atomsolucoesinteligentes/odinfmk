<?php

require_once(dirname(dirname(__FILE__)) . "/vendor/autoload.php");

use Odin\utils\Config;
use Odin\routes\Routes;

Config::init("teste/"); 
Routes::init();

Routes::get("/", function(){
    
    $model = new App\models\Model();
    
    $model->titulo = "Testado de Novo";
    $model->idUsuario = 2;
    
    $stmt = $model->save();
    
    var_dump($stmt);
    echo "<br>";
    
    var_dump($model->findLast("idQuadro"));
});

Routes::run();