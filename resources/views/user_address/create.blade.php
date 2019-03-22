@extends('layouts.app')
@section('title', '新增收货地址')

@section('content')

    <div class="row">
        <div class="col-md-10 offset-lg-1">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">
                        新增收货地址
                    </h2>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="post" action="{{route('user_address.store')}}">
                        <!-- 引入 csrf token 字段 -->
                    {{ csrf_field() }}
                    <!-- 注意这里多了 @change -->
                        <div class="form-group row" id="distpicker">
                            <label class="col-form-label col-sm-2 text-md-right">省市区</label>
                            <div class="col-sm-3">
                                <select class="form-control{{ $errors->has('province') ? ' is-invalid' : '' }}"
                                        name="province"></select>
                                @if ($errors->has('province'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('province') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                        name="city"> </select>
                                @if ($errors->has('city'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-3" name="district">
                                <select class="form-control{{ $errors->has('district') ? ' is-invalid' : '' }}"
                                        name="district"></select>
                                @if ($errors->has('district'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('district') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-md-right col-sm-2">详细地址</label>
                            <div class="col-sm-9">
                                <input type="text"
                                       class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                       name="address" value="{{old('address')}}">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-md-right col-sm-2">邮编</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control{{ $errors->has('zip') ? ' is-invalid' : '' }}"
                                       name="zip" value="{{old('zip')}}">
                                @if ($errors->has('zip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-md-right col-sm-2">姓名</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{old('contact_name')}}"
                                       class="form-control{{ $errors->has('contact_name') ? ' is-invalid' : '' }}"
                                       name="contact_name">
                                @if ($errors->has('contact_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-md-right col-sm-2">电话</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{old('contact_phone')}}"
                                       class="form-control{{ $errors->has('contact_phone') ? ' is-invalid' : '' }}"
                                       name="contact_phone">
                                @if ($errors->has('contact_phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('js/distpicker.min.js')}}"></script>
    <script>
        $(function () {
            //初始化城市联动
            $("#distpicker").distpicker({
                province: "{{old('provice')?:'请选择省'}}",
                city: "{{old('provice')?:'请选择市'}}",
                district: "{{old('provice')?:'请选择区'}}"
            });

        })
    </script>
@endpush
