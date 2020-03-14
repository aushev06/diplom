<?php

namespace App\Models\Views\Order;


use App\Models\Order;
use Carbon\Carbon;

class OrderViewModel
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var OrderViewDataModel[]
     */
    public $data = [];

    public function __construct($data)
    {
        $this->key = (new Carbon($data->key))->isoFormat("dddd, Do MMMM ");
        $orders = Order::whereDate(Order::ATTR_DATE_DELIVERY,date('Y-m-d',  strtotime($data['key'])))->get();

        foreach ($orders as $order) {
            $this->data[] = new OrderViewDataModel($order);
        }

    }
}
