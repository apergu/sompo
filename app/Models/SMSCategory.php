<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'sqlsrv';

    protected $table = 'Sompo_Category_SMS';

    protected $primaryKey = 'CategoryID';
}
