<?php
/**
 * Created by PhpStorm.
 * User: aushev
 * Date: 04.11.2019
 * Time: 19:59
 */

namespace App\Services;


use App\Http\Requests\FoodRequest;
use App\Models\Food;


class FoodService
{
    /**
     * @var Food
     */
    private $food;

    public function __construct(Food $food)
    {
        $this->food = $food;
    }

    /**
     * @param FoodRequest $request
     * @param Food $model
     */
    public function save(FoodRequest $request, Food $model)
    {
        $data = $request->all([
            $model::ATTR_PRICE,
            $model::ATTR_NAME
        ]);

        $model->fill($data);
        $model->user_id = $request->user()->id;

        if ($request->file('img')) {
            $model->img = $request->file('img')->store('img', 'public');
        }

        $model->save();

        return $model;
    }
}
