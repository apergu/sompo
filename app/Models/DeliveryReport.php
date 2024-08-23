<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryReport extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'delivery_reports';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'transid',
        'status',
        'referenceid',
        'description',
        'chargable',
        'drsource'
    ];

    public function mBlasting()
    {
        return $this->belongsTo(Blasting::class, 'referenceid', 'TxReference');
    }
}
