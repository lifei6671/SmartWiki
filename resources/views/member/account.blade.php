@extends('member')
@section('title')修改密码@endsection
@section('scripts')
<script type="text/javascript">
    function showError($msg) {
        $("#error-message").addClass("error-message").removeClass("success-message").text($msg);
    }
    function showSuccess($msg) {
        $("#error-message").addClass("success-message").removeClass("error-message").text($msg);
    }

$(function () {

   $("#account-form").ajaxForm({
       beforSubmit : function () {
           var oldPasswd = $("#oldPasswd").val();
           var newPasswd = $("#newPasswd").val();
           var confirmPassword = $("#confirmPassword").val();
           if(!oldPasswd ){
               showError("原始密码不能为空");
               return false;
           }
           if(!newPasswd){
               showError("新密码不能为空");
               return false;
           }
           if(!confirmPassword){
               showError("确认密码不能为空");
               return false;
           }
           if(configPasswd != newPasswd){
               showError("确认密码不正确");
               return false;
           }
       },
       success : function (res) {
            if(res.errcode == 0){
                showSuccess('保存成功');
            }else{
                showError(res.message);
            }
       }
   }) ;
});
</script>
@endsection
@section('content')
    <div class="member-box">
        <div class="box-head">
            <h4>修改密码</h4>
        </div>
        <div class="box-body">
            <form role="form" class="form-horizontal col-sm-5" method="post" action="{{route('member.account')}}" id="account-form">
                <div class="form-group">
                    <label for="oldPasswd">原始密码</label>
                    <input type="password" class="form-control" name="oldPasswd" id="oldPasswd" maxlength="20" placeholder="原始密码">
                </div>
                <div class="form-group">
                    <label for="newPasswd">新密码</label>
                    <input type="password" class="form-control" name="newPasswd" id="newPasswd" maxlength="20" placeholder="新密码">
                </div>
                <div class="form-group">
                    <label for="configPasswd">确认密码</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" maxlength="20" placeholder="确认密码">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">保存修改</button>
                    <span id="error-message"></span>
                </div>
            </form>
        </div>
    </div>
@endsection