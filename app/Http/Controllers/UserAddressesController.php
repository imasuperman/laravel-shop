<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

/**
 * 用户地址管理
 *
 * Class UserAddressesController
 *
 * @package App\Http\Controllers
 */
class UserAddressesController extends Controller
{

    public function index( Request $request )
    {
        $addresses=$request->user()->userAddress;

        return view( 'user_address.index' , compact( 'addresses' ) );
    }

    public function create()
    {
        return view( 'user_address.create' );
    }

    public function store( UserAddressRequest $request )
    {
        $request->user()->userAddress()->create( $request->all() );

        return redirect()->route( 'user_address.index' );
    }

    public function edit( UserAddress $userAddress )
    {
        $this->authorize( 'update' , $userAddress );

        return view( 'user_address.edit' , compact( 'userAddress' ) );
    }


    public function update( UserAddressRequest $request , UserAddress $userAddress )
    {
        $this->authorize( 'update' , $userAddress );
        $userAddress->update( $request->only( [
            'province' ,
            'city' ,
            'district' ,
            'address' ,
            'zip' ,
            'contact_name' ,
            'contact_phone' ,
        ] ) );

        return redirect()->route( 'user_address.index' );
    }

    public function destroy( UserAddress $userAddress )
    {
        $this->authorize( 'delete' , $userAddress );
        $userAddress->delete();

        return redirect()->route( 'user_address.index' );
    }
}
