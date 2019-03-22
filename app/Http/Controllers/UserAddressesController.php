<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 用户地址管理
 *
 * Class UserAddressesController
 * @package App\Http\Controllers
 */
class UserAddressesController extends Controller
{
    public function index ( Request $request )
    {
        $addresses = $request->user()->userAddress;
        return view( 'user_address.index' , compact( 'addresses' ) );
    }
}
