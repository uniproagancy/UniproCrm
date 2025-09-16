<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{

    public function model();

    public function getItemByEmail($email);

}
