<?php

namespace Freya\orm;

interface IMapper
{

    public function findById($id);

    public function save();

    public function loadClassProperties();

}
