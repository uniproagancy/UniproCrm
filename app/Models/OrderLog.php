<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLog extends Model
{
    //
    use SoftDeletes;

    protected $table = 'db_order_logs';

    protected $fillable = ['order_id', 'type', 'old_value', 'new_value', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }
}
