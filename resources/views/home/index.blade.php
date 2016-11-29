<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartWiki</title>

    <!-- Bootstrap -->
    <link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/static/styles/styles.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/static/bootstrap/js/html5shiv.min.js"></script>
    <script src="/static/bootstrap/js/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/scripts/jquery.min.js"></script>
</head>
<body>
<header class="navbar navbar-static-top smart-nav navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <a href="{{route('home.index')}}" class="navbar-brand">SmartWiki</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav navbar-right">
                @if(isset($member) && ($member->group_level == 0 || $member->group_level == 1))
                <li>
                    <a href="javascript:;" data-toggle="modal" data-target="#create-project" title="创建项目"><i class="fa fa-plus"></i></a>
                </li>
                @endif
                <li>
                    <a href="{{route('account.logout')}}" title="退出登录">
                        <i class="fa fa-sign-out"></i>
                    </a>
                </li>
                <li>
                    <a href="{{route('member.index')}}" class="img" title="个人中心">
                        <img src="{{$member['headimgurl']}}" class="img-circle" style="width: 43px;">
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
<div class="container smart-container">
    <div class="row">
        @if(count($lists) > 0)
        <ul class="project-box">
            @foreach($lists as $item)
                @include('widget.project',(array)$item)
            @endforeach
        </ul>
        <div>
            <?php echo $lists->render();?>
        </div>
        @else
            <div class="text-center" style="font-size: 20px;">暂无项目</div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
@include('widget.footer')

@if(isset($member) && ($member->group_level == 0 || $member->group_level == 1))
<!-- Modal -->
<div class="modal fade" id="create-project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">创建项目</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post" style="padding: 5px 15px;" id="create-form">
                    <div class="alert alert-danger alert-dismissible" role="alert" id="error-message" style="display: none">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                        <span></span>
                    </div>
                    <div class="form-group">
                        <label for="projectName" class="col-sm-2 control-label">项目名称</label>
                        <div class="col-sm-10">
                            <input type="text" name="projectName" id="projectName" class="form-control" placeholder="项目名称" maxlength="50" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">项目描述</label>
                        <div class="col-sm-10">
                            <textarea  name="description" id="description" class="form-control" placeholder="项目描述" maxlength="1000"  autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">项目权限</label>
                        <div class="col-sm-10 btn-group" data-toggle="buttons" id="project-passwd-buttons">
                            <label class="btn btn-default active"  id="projectPasswd1" data-toggle="tooltip" data-placement="auto" title="私密">
                                <input type="radio" name="projectPasswd"  autocomplete="off" checked value="1"><i class="fa fa-lock"></i>
                            </label>
                            <label class="btn btn-default" title="完全公开" id="projectPasswd2" data-toggle="tooltip" data-placement="auto">
                                <input type="radio" name="projectPasswd" autocomplete="off" value="2"><i class="fa fa-unlock"></i>
                            </label>
                            <label class="btn btn-default" title="加密公开" id="projectPasswd3" data-toggle="tooltip" data-placement="auto">
                                <input type="radio" name="projectPasswd" autocomplete="off" value="3"><i class="fa fa-unlock-alt"></i>
                            </label>
                            <input type="password" name="projectPasswdInput" class="form-control" style="width: 200px;margin-left: 110px;display: none" id="btn-project-passwd" placeholder="项目密码" maxlength="20"  autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" data-loading-text="创建中..." id="btn-create">创建</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/static/scripts/scripts.js" type="text/javascript"></script>


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
</body>
</html>