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
use App\Providers\AuthServiceProvider;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
        $orders = Order::query()
            ->distinct(Order::ATTR_DATE_DELIVERY)
            ->selectRaw("DATE_FORMAT(orders.date_delivery, '%d.%m.%Y') as 'key'")
            ->where(Order::ATTR_USER_ID, auth()->id())
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
        $model = Order::with(['client', 'foods'])->findOrFail($id);
        if (Gate::allows(AuthServiceProvider::ALLOW_IS_ME, $model)) {
            return new OrderResource($model);
        }


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
        /**
         * @var Order $model
         */
        $model = Order::query()->findOrFail($id);

        $model->delivery_type  = $request->post('delivery_type') ?? $model->delivery_type;
        $model->status         = $request->post('status') ?? $model->status;
        $model->payment_status = $request->post('payment_status') ?? $model->payment_status;

        $model->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Order::where(Order::ATTR_ID, $id);
        if (Gate::allows(AuthServiceProvider::ALLOW_IS_ME, $model)) {
            $model->delete();
            OrderFoods::where(OrderFoods::ATTR_ORDER_ID, $id)->delete();
        }


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
                ->where('orders.user_id', auth()->id())
                ->whereNull('foods.deleted_at')
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
                ->where('orders.user_id', auth()->id())
                ->whereNull('clients.deleted_at')
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
