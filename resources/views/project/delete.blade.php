@extends('member')
@section('title')删除项目@endsection

@section('scripts')

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
            $("#basic-form").ajaxForm({
                beforeSubmit : function () {
                    var password = $.trim($("#password").val());

                    if(!password){
                        return showError("登录密码不能为空");
                    }
                    $("#basic-form").find('button[type="submit"]').button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        if(document.referrer){
                            self.location = document.referrer;
                        }else{
                            self.location = "{{route('member.projects')}}";
                        }
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
            <h4>删除项目</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('project.delete',['id'=>$project->project_id])}}" id="basic-form">
                    <input type="hidden" name="project_id" value="{{$project->project_id}}">
                    <div class="form-group">
                        <label>项目名称</label>
                        <input type="text" value="{{$project->project_name}}" class="form-control disabled" disabled>
                    </div>
                    <div class="form-group">
                        <label for="password">登录密码</label><span style="font-size: 12px;color: #B4B4B4">&nbsp;(项目删除后将无法找回)</span>
                        <input type="password" class="form-control" name="password"  id="password" placeholder="登录密码" title="登录密码">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" onclick="self.location=document.referrer;">返回</button>
                        <button type="submit" class="btn btn-success" data-loading-text="删除中...">立即删除</button>
                        <span id="error-message" style="vertical-align: baseline"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection