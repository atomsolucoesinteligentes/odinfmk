<?php

namespace Odin\database\entity;

/**
 * Define os métodos de CRUD obrigatórios de uma entidade
 * @author Edney Mesquita
 */
interface ICrud {

    public function save();
    public function read();
    public function change();
    public function remove();
}
