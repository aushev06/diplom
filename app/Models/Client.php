<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $link
 * @property string $city
 * @property string $address
 * @property integer $user_id
 */
class Client extends Model
{
    const ATTR_ID      = 'id';
    const ATTR_NAME    = 'name';
    const ATTR_PHONE   = 'phone';
    const ATTR_LINK    = 'link';
    const ATTR_CITY    = 'city';
    const ATTR_ADDRESS = 'address';
    const ATTR_USER_ID = 'user_id';

    const TABLE_NAME = 'clients';

    const WITH_ORDERS = 'orders';

    protected $fillable = [
        self::ATTR_NAME,
        self::ATTR_LINK,
        self::ATTR_PHONE,
        self::ATTR_CITY,
        self::ATTR_ADDRESS
    ];

    protected $appends = [
        'letter'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
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
