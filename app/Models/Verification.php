<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Verification extends Model
{
    //
    use SoftDeletes;

    protected $table = "db_verifications";

    protected $fillable = ['admin_id', 'user_id', 'type', 'value'];
}
