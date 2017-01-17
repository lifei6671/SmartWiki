@extends('member')
@section('title')网站常量@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
       $(".delete-item").on("click",function () {
           var $btn = $(this).button('loading');
           var config_id = $(this).attr('data-id');
            var $then = $(this);
            $.post("{{route('member.setting.delete')}}/" + config_id).done(function (res) {
                $then.parents('tr').remove().empty();
                $btn.button('reset');
            }).fail(function () {
               layer.msg('删除失败');
                $btn.button('reset');
            });
       }) ;
    });
</script>
@endsection
@section('content')
    <div class="setting-box">
        <div class="box-head">
            <h4>网站常量</h4>
            <a href="{{route('member.setting.edit')}}" class="btn btn-success btn-sm pull-right" style="margin-top: 10px;">
                添加常量
            </a>
        </div>
        <div class="box-body" style="padding-right: 0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>名称</td>
                        <td>键名</td>
                        <td>添加时间</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    @if($lists->isEmpty())
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                    @else
                    @foreach($lists as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->key}}</td>
                            <td>{{$item->create_time}}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-item" data-id="{{$item->id}}" {{$item->config_type == 'system'?'disabled':''}} data-loading-text="删除中...">
                                    删除
                                </button>
                                <a href="{{route('member.setting.edit',['id'=>$item->id])}}" class="btn btn-sm btn-default">编辑</a>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection