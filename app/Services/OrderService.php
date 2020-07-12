<?php

namespace App\Services;


use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderFoods;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function save(OrderRequest $request, Order $order)
    {
        $data = $request->all([
            Order::ATTR_NAME,
            Order::ATTR_ADDRESS,
            Order::ATTR_CITY,
            Order::ATTR_PHONE,
            Order::ATTR_DATE_DELIVERY,
            Order::ATTR_COMMENT,
            Order::ATTR_CLIENT_ID,
            Order::ATTR_TOTAL_SUM,
        ]);

        $order->fill($data);
        $order->user_id = $request->user()->id;

//        $order->total_sum = 0;
        $order->save();

        foreach ($request->post('cart') as $food) {
            $this->setProperty([
                OrderFoods::ATTR_ID      => $food['id'] ?? null,
                OrderFoods::ATTR_FOOD_ID => $food['foodID'],
                OrderFoods::ATTR_COUNT   => $food['count'],
                OrderFoods::ATTR_UNIT    => $food['unit'],
                OrderFoods::ATTR_COMMENT => $food['comment'],
            ], $order->id);
        }

        return $order;
    }

    public function setProperty(array $food, int $orderID)
    {
        $model = OrderFoods::query()->find($food['id']) ?? new OrderFoods();
        $model->fill($food);
        $model->order_id = $orderID;
        Log::info('data', $model->getAttributes());
        $model->save();
    }
}
