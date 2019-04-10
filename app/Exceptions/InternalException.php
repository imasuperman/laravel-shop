<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * 系统异常  记录日志文件
 * Class InternalException
 *
 * @package App\Exceptions
 */
class InternalException extends Exception
{
    protected $msgForUser;

    public function __construct( string $message="" , string $msgForUser='系统内部错误' , int $code=0 , Throwable $previous=null )
    {
        parent::__construct( $message , $code , $previous );
        $this->msgForUser=$msgForUser;
    }

    public function render( Request $request )
    {
        if( $request->expectsJson() ){
            return response()->json( [ 'msg'=>$this->msgForUser ] , $this->code );
        }

        return view( 'pages.error' , [ 'msg'=>$this->msgForUser ] );
    }
}
