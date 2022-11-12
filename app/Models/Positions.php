<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{

    protected $fillable = ['symbol', 'trader_id', 'entryPrice', 'markPrice', 'roe', 'leverage','amount', 'yellow','existe', 'type', 'updateTime'];

    use HasFactory;
}
