@extends('member')
@section('title'){{$title}}@endsection
@section('styles')
    <link href="/static/webuploader/webuploader.css" rel="stylesheet">
    <link href="/static/cropper/cropper.css" rel="stylesheet">

@endsection
@section('scripts')
    <script type="text/javascript" src="/static/cropper/cropper.js"></script>
    <script type="text/javascript" src="/static/webuploader/webuploader.js"></script>

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
            $("#basic-form").on('click','#projectPasswd1,#projectPasswd2',function () {
                $("#btn-project-passwd").hide();
            });
            $("#basic-form").on('click','#projectPasswd3',function () {
                $("#btn-project-passwd").show();
            });
            $("#basic-form").ajaxForm({
                beforeSubmit : function () {
                    var name = $.trim($("#name").val());

                    if(!name){
                        return showError("项目名称不能为空");
                    }
                    $("#basic-form").find('button[type="submit"]').button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        showSuccess("保存成功");
                    }else{
                        showError(res.message);
                    }
                    $("#basic-form").find('button[type="submit"]').button('reset');
                }
            });
        });

    </script>
@endsection
@section('content')
    <div class="member-box">
        <div class="box-head">
            <h4>{{$title}}</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('project.edit',['id'=>$project->project_id])}}" id="basic-form">
                    <input type="hidden" name="project_id" value="{{$project_id or ''}}">
                    <div class="form-group">
                        <label for="user-account">项目名称</label>
                        <input type="text" class="form-control" name="name" value="{{$project->project_name or ''}}" id="name" placeholder="项目名称" title="项目名称">
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
                                <input type="radio" name="state" autocomplete="off" value="2"><i class="fa fa-unlock-alt"></i>
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
@endsection