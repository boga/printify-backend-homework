<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Order's items unit
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 *
 * @property-read  Product $product
 *
 */
class OrderItem extends Model
{
    protected $table = 'order_items';

    // region Relationships
    /**
     * The Order this OrderItem belongs to.
     */
//    public function order()
//    {
//        return $this->belongsTo('App\Order');
//    }
    /**
     * The Product this OrderItem belongs to.
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    // endregion
}
