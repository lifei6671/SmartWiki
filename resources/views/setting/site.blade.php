@extends('member')
@section('title')网站设置@endsection
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
                    var $btn = $("button[type='submit']").button('loading');
                },
                success : function (res) {
                    $("button[type='submit']").button('reset');
                    if(res.errcode == 0){
                        showSuccess("保存成功");
                    }else{
                        showError(res.message);
                    }
                }
            });
        });

    </script>
@endsection
@section('content')
    <div class="member-box">
        <div class="box-head">
            <h4>网站设置</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('setting.site')}}" id="basic-form">
                    <div class="form-group">
                        <label for="name">启用匿名访问</label>
                        <br/>
                        <label>
                            <input type="radio" name="ENABLE_ANONYMOUS" value="1" {{($ENABLE_ANONYMOUS) ? 'checked' : ''}}>是
                        </label>
                        <label>
                            <input type="radio" name="ENABLE_ANONYMOUS" value="0" {{(!$ENABLE_ANONYMOUS) ? 'checked' : ''}}>否
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="MAIL_TOKEN_TIME">找回密码邮件有效期</label>
                        <input class="form-control" name="MAIL_TOKEN_TIME" id="MAIL_TOKEN_TIME" value="{{$MAIL_TOKEN_TIME}}">
                    </div>
                    <div class="form-group">
                        <label for="SITE_NAME">站点名称</label>
                        <input name="SITE_NAME" id="SITE_NAME" value="{{$SITE_NAME}}" class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="ENABLED_HISTORY">启用文档历史</label><br/>
                        <label>
                            <input type="radio" name="ENABLED_HISTORY" value="1" {{($ENABLED_HISTORY) ? 'checked' : ''}}>是
                        </label>
                        <label>
                            <input type="radio" name="ENABLED_HISTORY" value="0" {{(!$ENABLED_HISTORY) ? 'checked' : ''}}>否
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="ENABLED_CAPTCHA">启用登录验证码</label><br/>
                        <label>
                            <input type="radio" name="ENABLED_CAPTCHA" value="1" id="ENABLED_CAPTCHA" {{($ENABLED_CAPTCHA) ? 'checked' : ''}}>是
                        </label>
                        <label>
                            <input type="radio" name="ENABLED_CAPTCHA" value="0" id="ENABLED_CAPTCHA" {{(!$ENABLED_CAPTCHA) ? 'checked' : ''}}>否
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="ENABLED_CAPTCHA">启用注册</label><br/>
                        <label>
                            <input type="radio" name="ENABLED_REGISTER" value="1" id="ENABLED_REGISTER" {{($ENABLED_REGISTER) ? 'checked' : ''}}>是
                        </label>
                        <label>
                            <input type="radio" name="ENABLED_REGISTER" value="0" id="ENABLED_REGISTER" {{(!$ENABLED_REGISTER) ? 'checked' : ''}}>否
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" data-loading-text="保存中...">立即保存</button>
                        <span id="error-message" style="vertical-align: baseline"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection