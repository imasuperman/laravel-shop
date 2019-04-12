@extends('layouts.app')
@section('title', '购物车')

@section('content')
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card">
                <div class="card-header">我的购物车</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>商品信息</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="product_list">
                        @foreach($cartItems as $item)
                            <tr data-id="{{ $item->productSku->id }}">
                                <td>
                                    <input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
                                </td>
                                <td class="product_info">
                                    <div class="preview">
                                        <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">
                                            <img src="{{ $item->productSku->product->image }}">
                                        </a>
                                    </div>
                                    <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
                                          <span class="product_title">
                                            <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
                                          </span>
                                        <span class="sku_title">{{ $item->productSku->title }}</span>
                                        @if(!$item->productSku->product->on_sale)
                                            <span class="warning">该商品已下架</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="price">￥{{ $item->productSku->price }}</span>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-remove">移除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        <form class="form-horizontal" role="form" id="order-form">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3 text-md-right">选择收货地址</label>
                                <div class="col-sm-9 col-md-7">
                                    <select class="form-control" name="address">
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3 text-md-right">备注</label>
                                <div class="col-sm-9 col-md-7">
                                    <textarea name="remark" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="offset-sm-3 col-sm-3">
                                    <button type="button" class="btn btn-primary btn-create-order">提交订单</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            //删除
            $('.btn-remove').click(function () {
                var id = $(this).closest('tr').data('id');
                swal({
                    title: "确认要将该商品移除？",
                    icon: "warning",
                    buttons: ['取消', '确定'],
                    dangerMode: true,
                }).then(function (willDelete) {
                    if (!willDelete) {
                        return;
                    }
                    axios.delete('/cart/' + id)
                        .then(function () {
                            location.reload();
                        })
                })
            })
            //全选和反选
            $('#select-all').change(function () {
                //当前元素是否选中
                var checked = $(this).prop('checked');
                $('input[name=select][type=checkbox]:not([disabled])').each(function () {
                    // 将其勾选状态设为与目标单选框一致
                    $(this).prop('checked', checked);
                });
            })
            //提交订单
            $('.btn-create-order').click(function () {
                var data = {
                    address_id: $('#order-form').find('select[name=address]').val(),//用户选择的地址 id
                    items: [],
                    remark: $('#order-form').find('textarea[name=remark]').val(),//备注
                };
                // 遍历 <table> 标签内所有带有 data-id 属性的 <tr> 标签，也就是每一个购物车中的商品 SKU
                $('table tr[data-id]').each(function () {
                    var $checkbox = $(this).find('input[name=select][type=checkbox]');
                    if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
                        return;
                    }
                    var $input = $(this).find('input[name=amount]');
                    if ($input.val() == 0 || isNaN($input.val())) {
                        return;
                    }
                    data.items.push({
                        sku_id: $(this).data('id'),
                        amount: $input.val(),
                    })
                })
                // console.log(data);
                axios.post('{{route('orders.store')}}', data).then(function () {
                    swal('订单提交成功', '', 'success');
                }, function (error) {
                    if (error.response.status === 422) {
                        var html = '<div>';
                        _.each(error.response.data.errors, function (errors) {
                            _.each(errors, function (error) {
                                html += error + '<br>';
                            })
                        })
                        html += '</div>';
                        swal({content: $(html)[0], icon: 'error'})
                    } else {
                        swal('系统错误', '', 'error');
                    }
                })
            })
        })
    </script>
@endpush
