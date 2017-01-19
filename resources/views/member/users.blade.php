@extends('member')
@section('title')
用户管理
@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
       $(".disabled-btn").on('click',function () {
           $(this).button('loading')
           var member_id = $(this).attr('data-id');
           var $then = $(this);

           $.post("{{route('member.users.delete')}}/"+ member_id).done(function (res) {
               $then.button('reset');
               setTimeout(function () {
                   if(res.errcode == 0){
                       if(res.data.state == 0){
                           $then.parents('tr').find('.user-state').removeClass('label-danger').addClass('label-success').text('正常');
                           $then.addClass('btn-danger').removeClass('btn-success').text('禁用').attr('data-loading-text','禁用中...');
                           // $then.text('禁用');

                       }else{
                           $then.parents('tr').find('.user-state').removeClass('label-success').addClass('label-danger').text('禁用');
                           $then.addClass('btn-success').removeClass('btn-danger').text('启用').attr('data-loading-text','启用中...');;
                           //$then.text('启用');
                       }
                   }else{
                       layer.msg('处理失败');
                   }
               },0);


           }).fail(function () {
               layer.msg('处理失败');
           });
       });
    });
</script>
@endsection
@section('content')
    <div class="project-box">
        <div class="box-head">
            <h4>用户管理</h4>
            <a href="{{route('member.users.edit')}}" class="btn btn-success btn-sm pull-right" style="margin-top: 10px;">
                添加用户
            </a>
        </div>
        <div class="box-body">
            <div class="user-list">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>账号</td>
                            <td>昵称</td>
                            <td>角色</td>
                            <td class="col-sm-3">邮箱</td>
                            <td>状态</td>
                            <td class="hidden-xs hidden-sm hidden-md">注册时间</td>
                            <td>操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $item)
                            <tr>
                                <td>{{$item->member_id}}</td>
                                <td>{{$item->account}}</td>
                                <td>{{$item->nickname}}</td>
                                <td>
                                    @if($item->group_level == 0)
                                        超级管理员
                                    @elseif($item->group_level == 1)
                                        普通用户
                                    @else
                                        访客
                                    @endif
                                </td>
                                <td>{{$item->email}}</td>
                                <td>
                                    @if($item->state == 0)
                                        <span class="label label-success user-state">正常</span>
                                    @elseif($item->state == 1)
                                        <span class="label label-danger user-state">禁用</span>
                                    @endif
                                </td>
                                <td class="hidden-xs hidden-sm hidden-md">{{$item->create_time}}</td>
                                <td>
                                    @if($item->group_level == 0)
                                        <button class="btn btn-danger btn-sm disabled-btn" data-id="{{$item->member_id}}" disabled>
                                            禁用
                                        </button>
                                    @else
                                    @if($item->state == 0)
                                        <button type="button" class="btn btn-danger btn-sm disabled-btn" data-id="{{$item->member_id}}"  data-loading-text="禁用中...">
                                            禁用
                                        </button>
                                    @elseif($item->state == 1)
                                        <button type="button" class="btn btn-success btn-sm disabled-btn" data-id="{{$item->member_id}}"  data-loading-text="启用中...">
                                            启用
                                        </button>
                                    @endif
                                    @endif
                                    <a href="{{route('member.users.edit',['id'=>$item->member_id])}}" class="btn btn-sm btn-default">编辑</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div>
        <nav>
            {{$lists->render()}}
        </nav>
    </div>
@endsection