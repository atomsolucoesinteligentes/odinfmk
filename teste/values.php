<?php

use App\classes\Usuario;

$nomes = ["Izac", "Hygor", "Nicollas", "Edney"];
$idades = [20, 35, 24, 20];

foreach($nomes as $key => $nome){
    ${$nome} = new Usuario();
    ${$nome}->nome = $nome;
    ${$nome}->idade = $idades[$key];
}