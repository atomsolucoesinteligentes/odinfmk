<?php

namespace Odin\database\orm;

interface IMapper
{

    public function findById($id);

    public function save();

    public function loadClassProperties();

}
