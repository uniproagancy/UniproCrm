<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{

    abstract public function model();

    public function getItemByEmail($email)
    {
        return $this->model()->where(['email' => $email])->first();
    }

}
