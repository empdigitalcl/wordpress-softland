<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sync extends Model
{
    protected $fillable = [
        'wooProductId',
        'laudusProductId',
        'status',
        'sku',
        'stockAvailable',
        'netPrice',
        'session',
        'woocType',
        'parentId'
    ];
    protected $table = 'sync';
    public $timestamps = false;

    public function scopeBySku($query, $sku)
    {
        if ($sku != null && $sku != '') {
            return $query->where('sku', $sku);
        }
    }
    public function scopePending($query)
    {
        $status = 1;
        return $query->where('status', $status);
    }

}
