<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Food;
use App\Models\Order;
use App\Http\Resources\Order as OrderResource;
use App\Models\OrderFoods;
use App\Models\Views\ClientViewDataModel;
use App\Models\Views\Food\FoodViewDataModel;
use App\Models\Views\Food\FoodViewModel;
use App\Models\Views\Order\OrderViewModel;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            $request->get('date_from', date("Y-m-d")),
            $request->get('date_to', date("Y-m-d", strtotime("2040-11-23")))
        ];

        $orders = Order::distinct(Order::ATTR_DATE_DELIVERY)
            ->selectRaw("DATE_FORMAT(orders.date_delivery, '%d.%m.%Y') as 'key'")
            ->when($filter, function ($query) use ($filter) {
                return $query->whereBetween(Order::ATTR_DATE_DELIVERY, $filter);
            })
            ->when($request->get('name'), function ($query) use ($request) {
                return $query->where(Order::ATTR_NAME, "LIKE", "%" . $request->get('name') . "%")
                    ->orWhere(Order::ATTR_PHONE, "LIKE", "%" . $request->get('name') . "%");
            })
            ->get()
            ->map(function ($order) {
                return new OrderViewModel($order);
            });


        return response()->json($orders);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $order = $this->service->save($request, new Order);

        return response()->json($order);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with(['client', 'foods'])->findOrFail($id);

        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Order::where(Order::ATTR_ID, $id)
            ->update([
                Order::ATTR_STATUS => $request->post('status')
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::where(Order::ATTR_ID, $id)->delete();
        OrderFoods::where(OrderFoods::ATTR_ORDER_ID, $id)->delete();

        return "";
    }

    public function stats(Request $request)
    {
        $filter = [
            $request->get('date_from', date("Y-m-d", strtotime('1970-01-01'))),
            $request->get('date_to', date("Y-m-d", strtotime("2040-11-23")))
        ];

        if ($request->get('type') === 'foods') {
            $data = OrderFoods::selectRaw('foods.*, sum(count) as count')
                ->leftJoin('orders', 'orders.id', '=', 'order_foods.order_id')
                ->leftJoin('foods', 'foods.id', '=', 'order_foods.food_id')
                ->when($filter, function ($query) use ($filter) {
                    return $query->whereBetween(Order::ATTR_DATE_DELIVERY, $filter);
                })
                ->groupBy('order_foods.food_id')
                ->orderByDesc($request->get('order') ?? 'count')
                ->get()
                ->map(function ($item) {
                    return new FoodViewDataModel($item);
                });
        } elseif ($request->get('type') === 'clients') {
            $data = Order::selectRaw('clients.*, sum(total_sum) as total, COUNT(orders.id) as count')
                ->leftJoin('clients', 'clients.id', '=', 'orders.client_id')
                ->when($filter, function ($query) use ($filter) {
                    return $query->whereBetween(Order::ATTR_DATE_DELIVERY, $filter);
                })
                ->groupBy('orders.client_id')
                ->orderByDesc($request->get('order') ?? 'count')
                ->get()
                ->map(function ($item) {
                    return new ClientViewDataModel($item);
                });
        }

        return response()->json($data);
    }

}
