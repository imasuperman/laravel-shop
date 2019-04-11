<?php

namespace App;

use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'email' , 'password' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password' , 'remember_token' ,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime' ,
    ];

    /**
     * 一对多关联用户地址
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAddress ()
    {
        return $this->hasMany( UserAddress::class );
    }

    /**
     * 多堆多关联商品 模型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteProducts(){
        return $this->belongsToMany(Product::class,'user_favorite_products','user_id','product_id')
            ->withTimestamps()
            ->orderBy('user_favorite_products.created_at','desc');
    }
}
