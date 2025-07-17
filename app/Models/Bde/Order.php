<?php

namespace App\Models\Bde;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'bde_bdd';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'member_id',
        'cart_id',
        'price',
        'amount',
        'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getDate()
    {
        $date = Carbon::parse($this->date);

        return match (true) {
            $date->isToday() => "Aujourd'hui",
            $date->isYesterday() => "Hier",
            default => $date->diffForHumans(),
        };
    }
}
