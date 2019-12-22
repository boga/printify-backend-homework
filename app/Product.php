<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Product model
 *
 * @property
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at

 * @property int $price
 * @property string $color
 * @property string $size
 * @property string $type
 *
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'color',
        'price',
        'size',
        'type',
    ];
}
