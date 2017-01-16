@extends('member')
@section('title')编辑配置@endsection

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
                    var name = $.trim($("#name").val());

                    if(!name){
                        $btn.button('reset');
                        return showError("名称不能为空");
                    }
                    var key = $.trim($("#key").val());

                    if(!key){
                        $btn.button('reset');
                        return showError('键名不能为空');
                    }
                },
                success : function (res) {
                    $("button[type='submit']").button('reset');
                    if(res.errcode == 0){
                        showSuccess("保存成功");
                        $("input[name='config_id']").val(res.data.id);
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
            <h4>编辑配置</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('member.setting.edit')}}" id="basic-form">
                    <input type="hidden" name="config_id" value="{{$id or ''}}">
                    <div class="form-group">
                        <label for="name">名称<strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="name" id="name" max="20" placeholder="名称" value="{{$name or ''}}"{{(isset($config_type) and $config_type=='system') ?' disabled':''}}>
                    </div>
                    <div class="form-group">
                        <label for="key">键名<strong class="text-danger">*</strong></label><span style="font-size: 12px;color: #B4B4B4">&nbsp;(必须以英文字母开头且只能包含英文字母数字和下划线)</span>
                        <input type="text" class="form-control" name="key" id="key" max="20" placeholder="键名" value="{{$key or ''}}"{{(isset($config_type) and $config_type=='system') ?' disabled':''}}>
                    </div>
                    <div class="form-group">
                        <label for="value">键值</label><span style="font-size: 12px;color: #B4B4B4">&nbsp(键值不能超过1000字符)</span>
                        <input type="text" class="form-control" title="键值" placeholder="键值" name="value" id="value" maxlength="1000" value="{{$value or ''}}">
                    </div>
                    <div class="form-group">
                        <label class="remark">备注</label><span style="font-size: 12px;color: #B4B4B4">&nbsp(备注不能超过1000字)</span>
                        <textarea class="form-control" rows="3" title="备注" name="remark" id="remark" maxlength="1000">{{$remark or ''}}</textarea>
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