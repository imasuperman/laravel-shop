<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use Illuminate\Http\Request;

/**
 * 用户地址管理
 *
 * Class UserAddressesController
 * @package App\Http\Controllers
 */
class UserAddressesController extends Controller
{
    /**
     * 用户地址列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ( Request $request )
    {
        $addresses = $request->user()->userAddress;
        return view( 'user_address.index' , compact( 'addresses' ) );
    }

    public function create ()
    {
        return view( 'user_address.create' );
    }

    public function store ( UserAddressRequest $request )
    {
        $request->user()->userAddress()->create( $request->all() );
        return redirect()->route('user_address.index');
    }
}
