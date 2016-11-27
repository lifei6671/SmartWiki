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

        });
    </script>
@endsection
@section('content')
    <div class="project-box">
        <div class="box-head">
            <h4>我的项目</h4>
            @if(is_can_create_project($member->member_id))
            <a href="{{route('project.edit')}}" class="btn btn-success btn-sm pull-right" style="margin-top: 10px;">
                添加项目
            </a>
            @endif
        </div>
        <div class="box-body">
            <div class="project-list">
                @foreach($lists as $item)

                <div class="project-item">
                    <dl>
                        <dt>
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

                        </dt>
                        <dd class="title">
                            <a href="{{route('home.show',['id'=>$item->project_id])}}" title="查看文档">{{$item->project_name}}</a>
                        </dd>
                        <dd class="other">
                            <span class="hint--bottom" title="最后发布"  data-toggle="tooltip" data-placement="bottom">
                                <i class="fa fa-clock-o"></i> {{!empty($item->modify_time) ? date('Y-m-d H:d:s',strtotime($item->modify_time)) : ''}}
                            </span>
                            <span class="hint--bottom" title="项目成员"  data-toggle="tooltip" data-placement="bottom"><i title="成员" class="fa fa-group"></i> {{$item->member_count}}</span>
                        </dd>
                        <dd class="operate">
                            @if($item->role_type == 0)
                                <button class="btn btn-danger btn-sm" title="退出" data-id="{{$item->project_id}}" data-toggle="tooltip" data-placement="bottom">
                                    <i class="fa fa-power-off"></i>
                                    退出
                                </button>
                                <a href="javascript:;" class="btn btn-warning disabled btn-sm"  title="删除项目" data-toggle="tooltip" data-placement="bottom">删除项目</a>
                            @elseif($item->role_type == 1)
                                <a href="{{route('project.members',['id'=>$item->project_id])}}" class="btn btn-danger btn-sm" title="管理文档成员" data-toggle="tooltip" data-placement="bottom">
                                    <i class="fa fa-gears"></i>
                                    管理
                                </a>
                                <a href="{{route('project.delete',['id'=>$item->project_id])}}" class="btn btn-warning btn-sm" title="删除项目" data-toggle="tooltip" data-placement="bottom">删除项目</a>
                            @endif
                            <a href="{{route('project.edit',['id'=>$item->project_id])}}" class="btn btn-primary btn-sm" title="编辑项目" data-toggle="tooltip" data-placement="bottom"><span class="fa fa-pencil"></span> 编辑项目</a>
                            <a class="btn btn-default  btn-sm" href="{{route('document.index',['id'=>$item->project_id])}}" target="_blank" title="编辑文档" data-toggle="tooltip" data-placement="bottom"><span class="btn-input"><i class="fa fa-edit"></i> 编辑文档</span></a>
                        </dd>
                    </dl>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    <div>
        <nav>
            {{$lists->render()}}
        </nav>
    </div>
@endsection