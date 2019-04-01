@extends('layouts.app')
@section('title','地址管理')
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card panel-default">
                <div class="card-header">
                    收货地址列表
                    <a href="{{route('user_address.create')}}" class="float-right text-decoration-none">添加收货地址</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>收货人</th>
                            <th>地址</th>
                            <th>邮编</th>
                            <th>电话</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($addresses as $address)
                            <tr>
                                <td>{{ $address->contact_name }}</td>
                                <td>{{ $address->full_address }}</td>
                                <td>{{ $address->zip }}</td>
                                <td>{{ $address->contact_phone }}</td>
                                <td>
                                    <a href="{{route('user_address.edit',$address)}}" class="btn btn-primary btn-sm">修改</a>
                                    <button type="button" onclick="del(this)" class="btn btn-danger btn-sm">删除</button>
                                    <form action="{{route('user_address.destroy',$address)}}" method="post">
                                        {{csrf_field()}}
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function del(obj) {
            swal({
                title: "确认要删除该地址？",
                icon: "warning",
                buttons: ['取消', '确定'],
                dangerMode: true,
            }).then(function(willDelete) { // 用户点击按钮后会触发这个回调函数
                // 用户点击确定 willDelete 值为 true， 否则为 false
                // 用户点了取消，啥也不做
                if (!willDelete) {
                    return;
                }
                $(obj).next('form').trigger('submit');
            });
        }
    </script>
@endpush
