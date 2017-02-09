<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SmartWiki" />
    <title>历史版本 - {{wiki_config('SITE_NAME','SmartWiki')}}</title>

    <!-- Bootstrap -->
    <link href="{{asset('static/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{asset('static/bootstrap/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('static/bootstrap/js/respond.min.js')}}"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('static/scripts/jquery.min.js')}}"></script>
    <style type="text/css">
        .container{margin: 5px auto;}
    </style>
</head>
<body>
<div class="container">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>#</td>
                <td class="col-sm-6">修改时间</td>
                <td class="col-sm-2">修改人</td>
                <td class="col-sm-1">操作</td>
            </tr>
            </thead>
            <tbody>
            @foreach($lists as $item)
                <tr>
                    <td>{{$item->history_id}}</td>
                    <td>{{$item->create_time}}</td>
                    <td>{{$item->account}}</td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{$item->history_id}}" data-loading-text="删除中...">
                            删除
                        </button>
                        <button class="btn btn-success btn-sm restore-btn" data-id="{{$item->history_id}}" data-loading-text="恢复中...">
                            恢复
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <nav>
       {{$lists->render()}}
    </nav>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('static/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript">
    $(function () {
       $(".delete-btn").on("click",function () {
          var id = $(this).attr('data-id');
           var $btn = $(this).button('loading');
           var $then = $(this);

           if(!id){
               layer.msg('参数错误');
           }else{
               $.ajax({
                   url : "{{route('document.history.delete')}}",
                   type : "post",
                   dataType : "json",
                   data : {"id":id},
                   success :function (res) {
                       if(res.errcode == 0){
                           $then.parents('tr').remove().empty();
                       }else{
                           layer.msg(res.message);
                       }
                   },
                   error : function () {
                       $btn.button('reset');
                   }
               })
           }
       });

        $(".restore-btn").on("click",function () {
            var id = $(this).attr('data-id');
            var $btn = $(this).button('loading');
            var $then = $(this);
            var index = parent.layer.getFrameIndex(window.name);

            if(!id){
                layer.msg('参数错误');
            }else{
                $.ajax({
                    url : "{{route('document.history.restore')}}",
                    type : "post",
                    dataType : "json",
                    data : {"id":id},
                    success :function (res) {
                        if(res.errcode == 0){
                            window.parent.SelectedId = res.data.doc_id;
                            parent.layer.close(index);
                        }else{
                            layer.msg(res.message);
                        }
                    },
                    error : function () {
                        $btn.button('reset');
                    }
                })
            }
        });
    });
</script>
</body>
</html>