<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews'; 
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'spot_id', 'user_id', 'rating', 'comment'
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id');
    }
}