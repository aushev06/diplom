<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderFoods
 * @property integer $id
 * @property integer $order_id
 * @property integer $food_id
 * @property integer $unit
 * @property integer $count
 * @property string $comment
 */
class OrderFoods extends Model
{
    const ATTR_ID       = 'id';
    const ATTR_ORDER_ID = 'order_id';
    const ATTR_FOOD_ID  = 'food_id';
    const ATTR_UNIT     = 'unit';
    const ATTR_COUNT    = 'count';
    const ATTR_COMMENT  = 'comment';

    protected $fillable = [
        self::ATTR_ORDER_ID,
        self::ATTR_FOOD_ID,
        self::ATTR_COUNT,
        self::ATTR_COUNT,
        self::ATTR_UNIT,
        self::ATTR_COMMENT
    ];
}
