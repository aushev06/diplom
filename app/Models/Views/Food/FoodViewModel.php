<?php

namespace App\Models\Views\Food;


use App\Models\Food;

class FoodViewModel
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var FoodViewDataModel[]
     */
    public $data = [];

    public function __construct($data)
    {
        $this->key = $data->key;


        $foods = Food::
        query()
            ->where(Food::ATTR_USER_ID, auth()->id())
            ->where(Food::ATTR_NAME, 'LIKE', $this->key . '%')->get();

        foreach ($foods as $food) {
            $this->data[] = new FoodViewDataModel($food);
        }

    }
}
