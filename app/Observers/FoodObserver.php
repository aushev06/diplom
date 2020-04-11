<?php

namespace App\Observers;

use App\Models\Food;
use App\Models\OrderFoods;

class FoodObserver
{
    /**
     * Handle the food "created" event.
     *
     * @param \App\Models\Food $food
     * @return void
     */
    public function created(Food $food)
    {
        //
    }

    /**
     * Handle the food "updated" event.
     *
     * @param \App\Models\Food $food
     * @return void
     */
    public function updated(Food $food)
    {
        //
    }

    /**
     * Handle the food "deleted" event.
     *
     * @param \App\Models\Food $food
     * @return void
     */
    public function deleted(Food $food)
    {
        $data = OrderFoods::query()->where(OrderFoods::ATTR_FOOD_ID, $food->id)->get();

        foreach ($data as $item) {
            $item->order()->delete();
        }
    }

    /**
     * Handle the food "restored" event.
     *
     * @param \App\Models\Food $food
     * @return void
     */
    public function restored(Food $food)
    {
        //
    }

    /**
     * Handle the food "force deleted" event.
     *
     * @param \App\Models\Food $food
     * @return void
     */
    public function forceDeleted(Food $food)
    {
        //
    }
}
