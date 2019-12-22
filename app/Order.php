<?php

namespace App;

use App\Helpers\IP;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Order model
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $country
 * @property int $status
 *
 * @property-read OrderItem[]|Collection $items
 *
 */
class Order extends Model
{
    const DEFAULT_COUNTRY = "US";

    const STATUS_DRAFT = 1;
    const STATUS_PLACED = 2;

    /**
     *
     * @throws Exception
     */
    public function getCost(): int
    {
        $this->load("items.product");
        if ($this->items->count() === 0) {
            throw new Exception("order is empty");
        }

        $cost = $this->items->reduce(function ($acc, $item) {
            /** @var OrderItem $item */
            return $acc + ($item->product->price * $item->quantity);
        }, 0);

        return $cost;
    }

    public function validateCountry(): bool
    {
        $this->fillCountry();
        /**
         * select count(1)
         * from orders as o
         * where 1 = 1
         * and o.country = 'US'
         * and o.updated_at < date_sub(now(), interval 2 second)
         *
         */

        /** @var Builder $orderCountForCountry */
        $orderCountForCountry = Order::where("country", $this->country)
            ->where("updated_at", "<", DB::raw(sprintf("date_sub(now(), interval %s)", config("orders.country_limit_timeframe"))))
            ->count();
        $ok = $orderCountForCountry > config("orders.country_limit");

        return $ok;
    }

    public function fillCountry(): bool
    {
        if (!empty($this->country)) {
            return true;
        }

        if (!($this->country = IP::getCountry())) {
            $this->country = self::DEFAULT_COUNTRY;
        }

        return $this->save();
    }


    public function place(): bool
    {
        $this->validate();
        $this->status = self::STATUS_PLACED;

        return $this->save();
    }

    // region Relationships
    public function items()
    {
        return $this->hasMany('App\OrderItem');
    }

    // endregion
    private function validate()
    {
        $cost = $this->getCost();
        if ($cost < 10 * 100 /* because we use cents */) {
            throw new Exception("order costs less then 10");
        }

        $this->validateCountry();

        return true;
    }
}
