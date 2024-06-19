<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blasting extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'Broadcast';

    protected $primaryKey = 'BroadcastID';
}
