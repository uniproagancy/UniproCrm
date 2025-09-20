<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{

    abstract public function model();

    public function getItemByEmail($email)
    {
        return $this->model()->where(['email' => $email])->first();
    }

    public function getItemByFields($fields)
    {
        return $this->model()->where($fields)->first();
    }

    public function create($data)
    {
        return $this->model()->create($data);
    }

    public function update($where, $data)
    {
        return $this->model()->where($where)->update($data);
    }

    public function forceDelete($where)
        {
            $this->model()->where($where)->forceDelete();
        }
}
