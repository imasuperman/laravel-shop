<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable=[ 'amount' ];

    /**
     * 关联用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }

    /**
     * 关联商品 sku
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productSku()
    {
        return $this->belongsTo( ProductSku::class );
    }
}
