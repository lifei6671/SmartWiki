@extends('member')
@section('title')我的项目@endsection
@section('scripts')
    <script type="text/javascript">
        function showError($msg) {
            $("#error-message").addClass("error-message").removeClass("success-message").text($msg);
        }
        function showSuccess($msg) {
            $("#error-message").addClass("success-message").removeClass("error-message").text($msg);
        }
        $(function () {
            $("[data-toggle='tooltip']").tooltip();

            $(".project-quit-btn").on('click',function () {
               var url = $(this).attr('data-url');
               var $then = $(this);
               $then.closest('li').remove().empty();
               if(url){
                    $.post(url,{},function(res){
                        if(res.errcode === 0){
                            $then.closest('li').slideUp(200,function () {
                               $then.remove().empty();
                            });
                        }else{
                            layer.msg(res.message);
                        }
                    },'json');
               }
            });
        });

    </script>
@endsection
@section('content')
    <div class="project-box">
        <div class="box-head">
            <h4>我的项目</h4>
            @if(is_can_create_project($member->member_id))
            <a href="{{route('project.edit')}}" class="btn btn-success btn-sm pull-right" style="margin-top: 10px;">添加项目</a>
            @endif
        </div>
        <div class="box-body">
            <div class="error-message">

            </div>
            <div class="project-list">
                <ul>
                @foreach($lists as $item)
                        <li>
                            <div>
                                <div>
                                    <div class="pull-left">
                                        @if($item->project_open_state == 0)
                                        <span class="hint--bottom" title="私密文档" data-toggle="tooltip" data-placement="bottom">
                                        <i class="fa fa-lock" title="私密文档"></i>
                                        </span>
                                        @elseif($item->project_open_state == 1)
                                        <span class="hint--bottom" title="公开文档" data-toggle="tooltip" data-placement="bottom">
                                        <i class="fa fa-unlock" title="公开文档"></i>
                                        </span>
                                        @elseif($item->project_open_state == 2)
                                        <span class="hint--bottom" title="加密文档" data-toggle="tooltip" data-placement="bottom">
                                        <i class="fa fa-unlock-alt" title="加密文档"></i>
                                        </span>
                                        @endif
                                        <a href="{{route('document.index',['id'=>$item->project_id])}}" title="编辑文档" data-toggle="tooltip" data-placement="bottom" target="_blank">{{$item->project_name}}</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{route('home.show',['id'=>$item->project_id])}}" title="查看文档" style="font-size: 12px;" data-toggle="tooltip" data-placement="bottom"  target="_blank"><i class="fa fa-eye"></i> 查看</a>
                                        @if($member->group_level === 0)
                                            <a href="{{route('project.members',['id'=>$item->project_id])}}" class="project-user-btn" title="管理文档成员" data-toggle="tooltip" data-placement="bottom"  style="font-size: 12px;"><i class="fa fa-user-plus"></i> 用户</a>
                                            <a href="{{route('project.edit',['id'=>$item->project_id])}}" title="编辑项目" data-toggle="tooltip" data-placement="left"  style="font-size: 12px;"><i class="fa fa-pencil"></i> 编辑</a>
                                        @else
                                            @if($item->role_type == 0 && $member->group_level != 0)
                                            <a href="###" class="project-quit-btn" title="退出" data-url="{{route('project.quit',['id' => $item->project_id])}}" data-toggle="tooltip" data-placement="bottom"  style="font-size: 12px;"><i class="fa fa-power-off"></i> 退出</a>
                                            @elseif($item->role_type == 1 || $member->group_level == 0)
                                            <a href="{{route('project.members',['id'=>$item->project_id])}}" class="project-user-btn" title="管理文档成员" data-toggle="tooltip" data-placement="bottom"  style="font-size: 12px;"><i class="fa fa-user-plus"></i> 用户</a>
                                            <a href="{{route('project.edit',['id'=>$item->project_id])}}" title="编辑项目" data-toggle="tooltip" data-placement="left"  style="font-size: 12px;"><i class="fa fa-pencil"></i> 编辑</a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="desc-text">
                                    @if(empty($item->description))
                                        &nbsp;
                                    @else
                                        <a href="{{route('document.index',['id'=>$item->project_id])}}" title="编辑文档" style="font-size: 12px;"  target="_blank">
                                            {{$item->description}}
                                        </a>
                                    @endif

                                </div>
                                <div class="info">
                                    <span title="创建时间" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-clock-o"></i> {!! date('Y/m/d H:i',strtotime($item->create_time)) !!}</span>
                                    <span style="display: inline-block;padding-left: 10px;" title="创建者" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-user"></i> {!! empty($item->nickname) ? $item->account : $item->nickname !!}</span>

                                    <span style="display: inline-block;padding-left: 10px;" title="文档数量" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-pie-chart"></i> {!! empty($item->doc_count) ? '0' : $item->doc_count !!}</span>

                                    <span style="display: inline-block;padding-left: 10px;" title="项目角色" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-user-secret"></i>
                                        @if($member->group_level === 0)
                                            @if(isset($item->role_type) && $item->role_type == 0)
                                                参与者
                                            @elseif(isset($item->role_type) && $item->role_type == 1)
                                                拥有者
                                            @else
                                                超级管理员{{ $item->role_type}}
                                            @endif
                                        @else
                                            @if($item->role_type == 0)
                                                参与者
                                            @elseif($item->role_type == 1)
                                                拥有者
                                            @endif
                                        @endif
                                    </span>

                                    @if(isset($item->last_document_time))
                                    <span style="display: inline-block;padding-left: 10px;" title="最后编辑" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-pencil"></i> 最后编辑: {{$item->last_document_user}} 于 {!! date('Y-m-d H:i',strtotime($item->last_document_time)) !!}</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                @endforeach
                </ul>
            </div>
        </div>

    </div>
    <div>
        <nav>
            {{$lists->render()}}
        </nav>
    </div>
@endsection