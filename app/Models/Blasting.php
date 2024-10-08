<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blasting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'sqlsrv';

    protected $table = 'Broadcast';

    protected $primaryKey = 'BroadcastID';

    public function deliveryReport()
    {
        return $this->belongsTo(DeliveryReport::class, 'TxReference', 'referenceid');
    }

    public function SMSCategory()
    {
        return $this->belongsTo(SMSCategory::class, 'CategoryID', 'CategoryID');
    }
}
