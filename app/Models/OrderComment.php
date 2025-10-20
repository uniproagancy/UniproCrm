<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderComment extends Model
{
    //
    use SoftDeletes;

    protected $table = 'db_order_comments';

    public function agent()
    {
        return $this->hasOne(Admin::class, 'id', 'agent_id');
    }
}
