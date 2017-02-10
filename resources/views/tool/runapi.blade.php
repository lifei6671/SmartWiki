@extends('home')
@section('title')接口测试工具@endsection
@section('styles')
    <link href="{{asset('static/bootstrap/icheck/skins/square/square.css')}}" rel="stylesheet">
    <link href="/static/codemirror/lib/codemirror.css" rel="stylesheet">
    <style>
        .CodeMirror { height: auto; border: 1px solid #ddd; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif;}
        .CodeMirror-scroll { max-height: 200px; }
        .CodeMirror pre { padding-left: 7px; line-height: 1.25; }
        .CodeMirror .CodeMirror-linenumber{font-size: 12px;min-width: 21px;}

    </style>
@endsection
@section('content')
    <div class="container tool-container">
        <div class="row">
            <div class="tool-api-method">
                <div class="row">
                <div class="col-lg-9">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" id="httpMethod" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px;">GET <span class="caret"></span></button>
                            <ul class="dropdown-menu" style="width: 100px;min-width: 100px;">
                                <li><a href="#">GET</a></li>
                                <li><a href="#">POST</a></li>
                                <li><a href="#">PUT</a></li>
                                <li><a href="#">PATCH</a></li>
                                <li><a href="#">DELETE</a></li>
                                <li><a href="#">COPY</a></li>
                                <li><a href="#">HEAD</a></li>
                                <li><a href="#">OPTIONS</a></li>
                                <li><a href="#">LINK</a></li>
                                <li><a href="#">UNLINK</a></li>
                                <li><a href="#">PURGE</a></li>
                                <li><a href="#">LOCK</a></li>
                                <li><a href="#">UNLOCK</a></li>
                                <li><a href="#">PROPFND</a></li>
                                <li><a href="#">VIEW</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <input type="text" class="form-control" aria-label="..." placeholder="请输入一个的URL">
                    </div><!-- /input-group -->
                </div>
                <div class="col-lg-3">
                    <button class="btn btn-primary" style="width: 70px">
                        发 送
                    </button>
                    <button class="btn btn-default" style="width: 70px">
                        保 存
                    </button>
                </div>
            </div>
            </div>
            <div class="row tool-api-parameter">
                <ul class="nav nav-tabs" id="parameter-tab">
                    <li role="presentation" class="active"><a href="#headers">Headers</a></li>
                    <li role="presentation"><a href="#body">Body</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="headers">
                        <table style="margin-top: 10px;width: 100%" class="parameter-active">
                            <tbody>
                            <tr>
                                <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                <td style="width: 50%;"><input type="text" class="input-text" placeholder="key"></td>
                                <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" placeholder="value"></td>
                                <td style="width: 100px;padding-left: 20px;">
                                    <a href="javascript:;" class="parameter-close hide">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="body">
                        <ul class="nav nav-tabs parameter-post-list">
                            <li><label href="#x-www-form-urlencodeed"><input type="radio" name="parameterType" checked>x-www-form-urlencodeed</label></li>
                            <li><label href="#raw"><input type="radio" name="parameterType">raw</label></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="x-www-form-urlencodeed">
                                <table style="margin-top: 10px;width: 100%" class="parameter-active">
                                    <tbody>
                                    <tr>
                                        <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                        <td style="width: 50%;"><input type="text" class="input-text" placeholder="key"></td>
                                        <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" placeholder="value"></td>
                                        <td style="width: 100px;padding-left: 20px;">
                                            <a href="javascript:;" class="parameter-close hide">
                                                <i class="fa fa-close"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="raw">
                                <textarea id="demotext"> 啊</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <script type="text/plain" id="parameter-template">
        <tr>
            <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
            <td style="width: 50%;"><input type="text" class="input-text" placeholder="key"></td>
            <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" placeholder="value"></td>
            <td style="width: 100px;padding-left: 20px;">
                <a href="javascript:;" class="parameter-close hide">
                    <i class="fa fa-close"></i>
                </a>
            </td>
        </tr>
    </script>
@endsection


@section('scripts')
<script type="text/javascript" src="{{asset('static/bootstrap/icheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="/static/codemirror/lib/codemirror.js"></script>
<script src="/static/codemirror/mode/xml/xml.js"></script>
<script src="/static/codemirror/mode/javascript/javascript.js"></script>
<script src="/static/codemirror/mode/css/css.js"></script>
<script src="/static/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="/static/codemirror/addon/edit/matchbrackets.js"></script>

<script type="text/javascript">
    function renderParameter() {
        $(".parameter-active>tbody>tr .input-text").off("focus");

        $(".parameter-active>tbody>tr:last-child .input-text").on("focus",function(){
            var html = $("#parameter-template").html();
            var $then = $(this).closest('tbody');

            $then.find("tr .hide").removeClass("hide");
            $then.append(html);
            $('input:checkbox').iCheck({
                checkboxClass: 'icheckbox_square',
                increaseArea: '10%'
            });
            renderParameter();
        });

    }
    $(function () {
        renderParameter();
        $(".dropdown-menu>li").on("click",function () {
            var text = $(this).text();
            $("#httpMethod").html(text + ' <span class="caret"></span>');
        });

        $('#parameter-tab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $(".parameter-post-list label").on("click",function (e) {
            $(this).tab('show');
        });

        var editor = CodeMirror.fromTextArea(document.getElementById("demotext"), {
            lineNumbers: true,
            mode: "text/html",
            matchBrackets: true,
            indentUnit:2,
            autofocus : true,
            height : "300px"
        });
    });
</script>
@endsection