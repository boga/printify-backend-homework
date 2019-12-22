<?php

namespace App\Http\Controllers;

use App\Helpers\IP;
use App\Order;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Mockery\Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $limit = min($params['limit'] ?? 20, 20);
        $offset = $params['offset'] ?? 0;
        /** @var $qb Builder */
        $qb = Order::skip($offset)->take($limit);

        if (!empty($params["product_type"])) {
            $qb = $qb->whereHas('items.product', function($query) use ($params) {
                $query->where('type', $params["product_type"]);
            });
        }

        $orders = $qb->get();

        return [
            "orders" => $orders,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Order|\Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Order $order */
        $order = Order::find($id);
        if (empty($order)) {
            throw new Exception("order not found");
        }

        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function calculateCost($id)
    {
        /** @var Order $order */
        $order = Order::find($id);
        if (empty($order)) {
            throw new Exception("order not found");
        }

        return [
            "cost" => $order->getCost(),
        ];
    }

    public function place($id)
    {
        /** @var Order $order */
        $order = Order::find($id);
        if (empty($order)) {
            throw new Exception("order not found");
        }
        $order->place();

        return [
            "order" => $order,
        ];
    }
}
