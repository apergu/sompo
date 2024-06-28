<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'Customer';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'zd_id',
        'name',
        'email',
        'tags'
    ];
}
