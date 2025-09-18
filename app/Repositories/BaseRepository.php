<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{

    abstract public function model();

    public function getItemByEmail($email)
    {
        return $this->model()->where(['email' => $email])->first();
    }

    public function create($data)
    {
        return $this->model()->create($data);
    }

    public function getItemByFields($fields)
    {
        return $this->model()->where($fields)->get();
    }

    public function update($where, $data)
    {
        return $this->model()->where($where)->update($data);
    }
}
