<?php

namespace Odin\database;

interface IMapper
{

    public function findById($id);

    public function  where();

    public function save();

    public function loadClassProperties();

}
