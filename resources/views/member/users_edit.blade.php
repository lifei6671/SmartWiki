@extends('member')
@section('title')编辑用户@endsection
@section('styles')
    <link href="{{asset('static/webuploader/webuploader.css')}}" rel="stylesheet">
    <link href="{{asset('static/cropper/cropper.css')}}" rel="stylesheet">

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

            $("#basic-form").ajaxForm({
                beforeSubmit : function () {
                    var account = $.trim($("#user-account").val());

                    if(!account){
                        return showError("账号不能为空");
                    }
                    var passwd = $("#user-passwd").val();

                    var memberId = $("input[name='member_id']").val();
                    if(!memberId && !passwd){
                       return showError('密码不能为空');
                    }
                    var email = $.trim($("#user-email").val());
                    if(!email){
                        return showError('邮箱不能为空');
                    }
                    $("#saveMember").button('loading');
                },
                success : function (res) {
                    if(res.errcode == 0){
                        showSuccess("保存成功");
                        $("#user-account").addClass('disabled').attr("disabled","disabled");
                    }else{
                        showError(res.message);
                    }
                    $("#saveMember").button('reset');
                },
                error : function () {
                    showError('服务器错误');
                    $("#saveMember").button('reset');
                }
            });
        });

    </script>
@endsection
@section('content')
    <div class="member-box">
        <div class="box-head">
            <h4>个人资料</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('member.users.edit')}}" id="basic-form">
                    <input type="hidden" name="member_id" value="{{$member_id or ''}}">
                    <div class="form-group">
                        <label for="user-account">用户名</label>
                        @if(isset($member_id) and $member_id > 0)
                            <input type="text" class="form-control disabled" name="userAccount" value="{{$account or ''}}" disabled id="user-account">
                        @else
                            <input type="text" class="form-control" name="userAccount"  id="user-account" placeholder="账号">
                        @endif

                    </div>
                    <div class="form-group">
                        <label for="user-nickname">密码</label><span style="font-size: 12px;color: #B4B4B4">&nbsp;(为空则不修改)</span>
                        <input type="password" class="form-control" name="userPasswd" id="user-passwd" max="20" placeholder="密码">
                    </div>
                    <div class="form-group">
                        <label for="user-nickname">昵称</label>
                        <input type="text" class="form-control" name="userNickname" id="user-nickname" max="20" placeholder="昵称" value="{{$nickname or ''}}">
                    </div>
                    <div class="form-group">
                        <label for="user-email">邮箱<strong class="text-danger">*</strong></label>
                        <input type="email" class="form-control" value="{{$email or ''}}" id="user-email" name="userEmail" max="100" placeholder="邮箱">
                    </div>
                    <div class="form-group">
                        <label>手机号</label>
                        <input type="text" class="form-control" id="user-phone" name="userPhone" maxlength="20" title="手机号码" placeholder="手机号码" value="{{$phone or ''}}">
                    </div>
                    <div class="form-group">
                        <label for="group_level">角色</label>
                        <select class="form-control" name="group_level" id="group_level">
                            <option value="0"{{ (isset($group_level) and $group_level == 0) ? ' selected' : ''}}>超级管理员</option>
                            <option value="1"{{ (!isset($group_level) or $group_level == 1) ? ' selected' : ''}}>普通用户</option>
                            <option value="2"{{(isset($group_level) and $group_level == 2) ? ' selected' : ''}}>访客</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="description">描述</label>
                        <textarea class="form-control" rows="3" title="描述" name="description" id="description" maxlength="500">{{$description or ''}}</textarea>
                        <p style="color: #999;font-size: 12px;">描述不能超过500字</p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" onclick="self.location=document.referrer;">返回</button>
                        <button type="submit" id="saveMember" class="btn btn-success" data-loading-text="正在保存...">立即保存</button>
                        <span id="error-message" style="vertical-align: baseline"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection