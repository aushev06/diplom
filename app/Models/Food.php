<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Food
 * @property integer $id
 * @property string $name
 * @property integer $price
 * @property string $img
 * @property integer $user_id
 */
class Food extends Model
{
    use SoftDeletes;

    const ATTR_ID      = 'id';
    const ATTR_NAME    = 'name';
    const ATTR_PRICE   = 'price';
    const ATTR_IMG     = 'img';
    const ATTR_USER_ID = 'user_id';

    const TABLE_NAME = 'foods';



    public $fillable = [
        self::ATTR_NAME,
        self::ATTR_PRICE,
        self::ATTR_IMG
    ];

    protected $appends = [
        'image',
        'letter',
    ];

    public function getImageAttribute()
    {
        return $this->attributes['image'] = ($this->img) ? url('storage/' . $this->img) : null;
    }

    /**
     * @return string
     *
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function getLetterAttribute() : string {
        return mb_substr($this->name, 0, 1);
    }
}
