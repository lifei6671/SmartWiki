@extends('member')
@section('title')
{{$project_name}}用户列表
@endsection
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
        var addBtn = $("#add-member-btn");

        $("#form-member").ajaxForm({
            dataType : "json",
           beforeSubmit : function () {
               var account = $.trim($("#account").val());
               if(!account){
                   return showError('请输入账号');
               }
               addBtn.button('loading')
           } ,
            success : function (res) {
                if(res.errcode == 0){
                    showSuccess('添加成功');
                    $("#form-member").after(res.data);
                }else{
                    showError(res.message);
                }
                addBtn.button('reset');
            },
            error :function () {
                showError('服务器错误');
                addBtn.button('reset');
            }
        });

        $(".delete-btn").on('click',function () {
            var $then = $(this);
            var account = $(this).attr("data-id");
            if(!account){
                alert("参数错误");
            }else{
                var $btn = $(this).button('loading');
                $.post("{{route('project.members.add',['id'=>$project_id])}}",{"type":"delete","account":account})
                        .done(function (res) {
                            $btn.button('reset');
                            $then.parents('.user-item').remove().empty();
                        }).fail(function () {
                    $btn.button('reset');
                   alert("处理失败");
                });
            }
        });
    });
</script>
@endsection
@section('content')
    <div class="project-box">
        <div class="box-head">
            <h4>{{$project_name}} - 用户列表</h4>
        </div>
        <div class="box-body">
            <div class="user-list">
                <form role="form" method="post" class="form-inline" action="{{route('project.members.add',['id'=>$project_id])}}" id="form-member">
                    <input type="hidden" name="project_id" value="{{$project_id}}">
                    <input type="hidden" name="type" value="add">
                    <div class="form-group">
                        <input type="text" name="account" id="account" placeholder="账号" class="form-control">
                        <button class="btn btn-success" data-loading-text="添加中..." id="add-member-btn"> 添加</button>
                        <span id="error-message"></span>
                    </div>
                </form>
                @foreach($users as $item)
                    @include('widget.project_member',['item'=>$item])
                @endforeach
            </div>

        </div>
    </div>
@endsection