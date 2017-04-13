@extends('member')
@section('title'){{$title}}@endsection
@section('styles')
    <link href="{{asset('static/webuploader/webuploader.css')}}" rel="stylesheet">
    <link href="{{asset('static/cropper/cropper.css')}}" rel="stylesheet">
    <style type="text/css">
        .box-operate{margin-top: 10px;}
        .box-operate>a,.box-operate>label{display: inline-block;margin-left: 5px;}
    </style>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('static/cropper/cropper.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/webuploader/webuploader.js')}}"></script>

    <script type="text/javascript">
        function showError($msg) {
            $("#error-message").addClass("error-message").removeClass("success-message").text($msg);
            return false;
        }
        function showSuccess($msg) {
            $("#error-message").addClass("success-message").removeClass("error-message").text($msg);
            return true;
        }

        $(function () {
            $("#basicForm").on('click','#projectPasswd1,#projectPasswd2',function () {
                $("#btn-project-passwd").hide();
            }).on('click','#projectPasswd3',function () {
                $("#btn-project-passwd").show();
            });



            $("#basicForm").ajaxForm({
                beforeSubmit : function () {
                    var name = $.trim($("#name").val());

                    if(!name){
                        return showError("项目名称不能为空");
                    }
                    $("#basicForm").find('button[type="submit"]').button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        $("#project_id").val(res.data.project_id);
                        $("#basicForm").attr("action",res.data.url);
                        showSuccess("保存成功");
                    }else{
                        showError(res.message);
                    }
                    $("#basicForm").find('button[type="submit"]').button('reset');
                }
            });


        });

    </script>
    @if(isset($is_owner) && $is_owner)
        <script type="text/javascript">
            $("#deleteForm").ajaxForm({
                beforeSubmit : function () {
                    var password = $.trim($("#deletePassword").val());

                    if(!password){
                        return layer.msg("登录密码不能为空");
                    }
                    $("#deleteForm").find('button[type="submit"]').button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        self.location = "{{route('member.projects')}}";
                    }else{
                        layer.msg(res.message);
                    }
                    $("#deleteForm").find('button[type="submit"]').button('reset');
                },
                error : function () {
                    layer.msg('服务器错误');
                    $("#deleteForm").find('button[type="submit"]').button('reset');
                }
            });
            $("#transferForm").ajaxForm({
                dataType : "json",
                beforeSubmit : function () {
                    $("#transferForm").find('button[type="submit"]').button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        self.location = "{{route('member.projects')}}";
                    }else{
                        layer.msg(res.message);
                    }
                    $("#transferForm").find('button[type="submit"]').button('reset');
                },
                error : function () {
                    layer.msg('服务器错误');
                    $("#transferForm").find('button[type="submit"]').button('reset');
                }
            });
        </script>
    @endif
@endsection
@section('content')
<div class="member-box">
        <div class="box-head">
            <h4>{{$title}}</h4>
            @if((isset($is_owner) && $is_owner) || $member->group_level === 0)
            <div class="box-operate pull-right">
                <label class="btn btn-success btn-sm pull-right" title="删除项目" data-toggle="modal" data-target="#projectTransfer">
                    转让项目
                </label>
                <label class="btn btn-danger btn-sm pull-right" title="删除项目" data-toggle="modal" data-target="#projectDelete">
                    删除项目
                </label>
            </div>
            @endif
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('project.edit',['id'=>$project->project_id])}}" id="basicForm">
                    <input type="hidden" name="project_id" id="project_id" value="{{$project_id or ''}}">
                    <div class="form-group">
                        <label for="user-account">项目名称</label>
                        <input type="text" class="form-control" name="name" value="{{$project->project_name or ''}}" id="name" placeholder="项目名称" title="项目名称" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label">项目权限</label>
                        <div class="col-sm-12 btn-group" style="padding-left: 0;" data-toggle="buttons" id="project-passwd-buttons">
                            <label class="btn btn-default{{$project->project_open_state == 0 ?' active':''}}"  id="projectPasswd1" data-toggle="tooltip" data-placement="auto" title="私密">
                                <input type="radio" name="state"  autocomplete="off" checked value="0"><i class="fa fa-lock"></i>
                            </label>
                            <label class="btn btn-default{{$project->project_open_state == 1 ?' active':''}}" title="完全公开" id="projectPasswd2" data-toggle="tooltip" data-placement="auto">
                                <input type="radio" name="state" autocomplete="off" value="1"><i class="fa fa-unlock"></i>
                            </label>
                            <label class="btn btn-default{{$project->project_open_state == 2 ?' active':''}}" title="加密公开" id="projectPasswd3" data-toggle="tooltip" data-placement="auto">
                                <input type="radio" name="state" autocomplete="off" value="2" ><i class="fa fa-unlock-alt"></i>
                            </label>

                            <input type="password" name="password" class="form-control" style="width: 200px;margin-left: 110px;{{$project->project_open_state == 2 ? '':'display: none;'}}" id="btn-project-passwd" placeholder="项目密码" maxlength="20"  autocomplete="off" value="{{$project->project_password}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>项目版本</label>
                        <input type="text" class="form-control" name="version" placeholder="项目版本" maxlength="20" value="{{$project->version or ''}}">
                    </div>
                    <div class="form-group">
                        <label class="description">描述</label>
                        <textarea class="form-control" rows="3" title="描述" name="description" id="description" maxlength="500">{{$project->description or ''}}</textarea>
                        <p style="color: #999;font-size: 12px;">描述不能超过500字</p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" onclick="self.location=document.referrer;">返回</button>
                        <button type="submit" class="btn btn-success" data-loading-text="保存中...">立即保存</button>
                        <span id="error-message" style="vertical-align: baseline"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@if((isset($is_owner) && $is_owner) || $member->group_level === 0)
<!-- Delete Project Modal -->
<div class="modal fade" id="projectDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post" action="{{route('project.delete',['id'=>$project->project_id])}}" id="deleteForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">删除项目</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="project_id" value="{{$project->project_id}}">
                    <div class="form-group">
                        <label for="password">登录密码</label><span style="font-size: 12px;color: #B4B4B4">&nbsp;(项目删除后将无法找回)</span>
                        <input type="password" class="form-control" name="password"  id="deletePassword" placeholder="登录密码" title="登录密码">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger" data-loading-text="正在删除...">确定删除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Project Modal -->
<div class="modal fade" id="projectTransfer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" method="post" action="{{route('project.transfer',['id'=>$project->project_id])}}" id="transferForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">转让项目</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="project_id" value="{{$project->project_id}}">
                    <div class="form-group">
                        <label for="password">请输入要转让的用户名</label>
                        <input type="text" class="form-control" name="account"  id="transferAccount" placeholder="用户账号" title="用户账号">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-success" data-loading-text="正在转让...">立即转让</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection