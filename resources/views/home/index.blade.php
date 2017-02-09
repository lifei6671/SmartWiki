@extends('home')
@section('title')首页@endsection
@section('content')
<div class="container smart-container">
    <div class="row">
        @if(count($lists) > 0)
            <ul class="project-box">
                @foreach($lists as $item)
                    @include('widget.project',(array)$item)
                @endforeach
            </ul>
            <div class="clearfix"></div>
            <div class="manual-page">
                <?php echo $lists->render();?>
            </div>
        @else
            <div class="text-center" style="font-size: 20px;">暂无项目</div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
@endsection

@section('modelDialog')

@endsection

@section('scripts')
<script type="text/javascript">

    function showErrorMessage($msg) {
        $("#error-message>span").text($msg).parent('div').show();
    }

    $(function () {
        var modalHtml = $("#create-project").find('.modal-body').html();

        //弹出提示
        $("[data-toggle='tooltip']").tooltip();

        $("#projectName,#btn-project-passwd").on('focus',function () {
            $(this).tooltip('destroy').parent('div').removeClass('has-error');;
        });

        $("#create-project").on('hidden.bs.modal',function () {
            $("#create-project").find('.modal-body').html(modalHtml);
        });

        $("#create-project").on('click','#projectPasswd1,#projectPasswd2',function () {
            $("#btn-project-passwd").hide();
        });
        $("#create-project").on('click','#projectPasswd3',function () {
            $("#btn-project-passwd").show();
        });

        $("#btn-create").on('click',function () {
            var $btn = $(this).button('loading');

            var name = $.trim($("#projectName").val());

            if(name == "") {
                $("#projectName").tooltip({placement: "auto", title: "项目名称不能为空", trigger: 'manual'})
                        .tooltip('show')
                        .parent('div').addClass('has-error');
                $btn.button('reset');
                return false;
            }
            if($("#btn-project-passwd").css('display') == 'block'){
                var passwd = $.trim($("#btn-project-passwd").val());
                if(passwd == ""){
                    $("#btn-project-passwd").tooltip({placement: "auto", title: "项目名称不能为空", trigger: 'manual'})
                            .tooltip('show')
                            .parent('div').addClass('has-error');
                    $btn.button('reset');
                    return false;
                }
            }
            $.ajax({
                url : "{{route('project.create')}}",
                type :"post",
                dataType :"json",
                data : $("#create-form").serializeArray(),
                success : function (res) {
                    console.log(res);
                    if(res.errcode == 20002){
                        $(".project-box").prepend(res.data.body);
                        $("#create-project").modal('hide');
                    }else{
                        showErrorMessage(res.message);
                    }
                    $btn.button('reset');
                },
                error : function () {
                    showErrorMessage('服务器异常');
                    $btn.button('reset');
                }
            });

            return false;
        });
    });

</script>
@endsection