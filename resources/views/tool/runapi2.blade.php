@extends('home')
@section('title')接口测试工具@endsection
@section('styles')
    <link href="{{asset('static/bootstrap/icheck/skins/square/square.css')}}" rel="stylesheet">
    <link href="/static/codemirror/lib/codemirror.css" rel="stylesheet">
    <style type="text/css">

        .CodeMirror { height: 250px; border: 1px solid #ddd; font-family: "Courier New", 'Source Sans Pro', Helvetica, Arial, sans-serif;font-size: 12px;}
        .CodeMirror-scroll { max-height: 250px; }
        .CodeMirror pre { padding-left: 7px; line-height: 1.25; }
        .CodeMirror .CodeMirror-linenumber{font-size: 12px;min-width: 21px;}

        .tool-container{
            min-width: 500px;
        }
        .tool-api-response{
            background: #FAFAFA;
            border-top: 1px solid #DDDDDD;
        }
        .response-info{
            line-height: 40px;
            font-size: 12px;
            margin-right: 15px;
            color: #989898;
        }
        .response-info .result{
            color: #0978EE;
        }
        .tool-api-response .nav-tabs li a{
            border-bottom: 2px solid transparent;
        }
        .tool-api-response .nav-tabs li.active a{
            border: 0;
            background: inherit;
            border-bottom: 2px solid #F47023;
        }
        .description{

        }
    </style>
@endsection
@section('content')
    <div class="container tool-container">
        <div class="row">
                <div class="description">
                    <strong>使用说明：</strong>
                    <ul>
                        <li>跨域请求请在服务器端添加响应头 Access-Control-Allow-Origin:{{$_SERVER['HTTP_HOST']}}</li>
                    </ul>
                </div>

                <div class="tool-api-method">
                    <div class="row">
                        <div class="col-lg-9 col-sm-8 col-xs-7">
                            <div class="input-group">
                                <div class="input-group-btn" id="btn-http-group">
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
                                <input type="text" class="form-control" id="requestUrl" aria-label="..." placeholder="请输入一个的URL" value="http://wiki.minho.com/tool/runapi">
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-lg-3 col-sm-4 col-xs-5">
                            <button type="button" id="sendRequest" class="btn btn-primary" style="width: 70px"> 发 送</button>

                            <div class="btn-group">
                                <button class="btn btn-default" style="width: 70px">
                                    保 存
                                </button>
                                <button class="btn btn-default dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">保存到文档</a></li>
                                    <li><a href="#">生成 Markdown</a> </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row tool-api-parameter">
                    <ul class="nav nav-tabs" id="parameter-tab">
                        <li role="presentation" class="active"  href="#headers"><a href="#headers">Headers</a></li>
                        <li role="presentation"  href="#body"><a href="#body">Body</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="headers">
                            <table style="margin-top: 10px;width: 100%" class="parameter-active">
                                <tbody>
                                <tr>
                                    <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                    <td style="width: 50%;"><input type="text" class="input-text" name="key" placeholder="key"></td>
                                    <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
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
                                <li href="#x-www-form-urlencodeed"><label><input type="radio" name="parameterType" checked value="x-www-form-urlencodeed">x-www-form-urlencodeed</label></li>
                                <li href="#raw"><label><input type="radio" name="parameterType" value="raw">raw</label></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="x-www-form-urlencodeed">
                                    <table style="margin-top: 10px;width: 100%" class="parameter-active">
                                        <tbody>
                                        <tr>
                                            <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
                                            <td style="width: 50%;"><input type="text" class="input-text" name="key" placeholder="key"></td>
                                            <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
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
                                    <textarea id="demotext"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row tool-api-response">
                    <ul class="nav nav-tabs">
                        <li href="#responseBody" class="active"><a href="javascript:;">Body</a> </li>
                        <li href="#responseCookie"><a href="javascript:;">Cookies</a> </li>
                        <li href="#responseHeader"><a href="javascript:;">Header</a> </li>
                        <div class="pull-right response-info">
                            <span>Status: <span class="result" id="httpCode">0</span></span>&nbsp;&nbsp;
                            <span>Time: <span class="result" id="httpTime">0 ms</span></span>
                        </div>
                    </ul>
                    <div class="tab-content" style="padding-top: 10px;">
                        <div role="tabpanel" class="tab-pane active" id="responseBody">
                            <textarea id="responseBodyContainer" style="display: none;"></textarea>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="responseCookie">
                            <table class="table table-condensed">
                                <thead>
                                <tr><th>Name</th><th>Value</th><th>Domian</th><th>Path</th><th>Expires</th><th>HTTP</th><th>Secure</th></tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="responseHeader">

                        </div>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
    </div>
    <script type="text/plain" id="parameter-template">
        <tr>
            <td style="width: 100px;padding-right: 20px;"><label class="hide"><input type="checkbox" checked></label></td>
            <td style="width: 50%;"><input type="text" class="input-text" placeholder="key" name="key"></td>
            <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value"></td>
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
<script type="text/javascript" src="{{asset('static/layer/layer.js')}}"></script>
<script type="text/javascript" src="/static/codemirror/lib/codemirror.js"></script>
<script src="/static/codemirror/mode/xml/xml.js"></script>
<script src="/static/codemirror/mode/javascript/javascript.js"></script>
<script src="/static/codemirror/mode/css/css.js"></script>
<script src="/static/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="/static/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="{{asset('static/scripts/runapi.js')}}"></script>
<script type="text/javascript">
    window.RawEditor = null;

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
        $("#btn-http-group .dropdown-menu>li").on("click",function () {
            var text = $(this).text();
            $("#httpMethod").html(text + ' <span class="caret"></span>');
        });

        $('#parameter-tab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $(".parameter-post-list li,.tool-api-response .nav-tabs>li").on("click",function (e) {
            $(this).tab('show');
        });

        $(".parameter-post-list>li[href='#raw']").on("shown.bs.tab",function (e) {
            if(!window.RawEditor) {
                window.RawEditor = CodeMirror.fromTextArea(document.getElementById("demotext"), {
                    lineNumbers: true,
                    mode: "text/javascript",
                    matchBrackets: true,
                    indentUnit: 2,
                    autofocus: true,
                });
            }
        });

        $("#sendRequest").on("click",function () {
           var url = $("#requestUrl").val();
           if(!url){
               layer.msg("请输入一个URL");
           }
           var method = $("#httpMethod").text();
           var runApi = new RunApi();

            var header = runApi.resolveRequestHeader();
            var body = runApi.resolveRequestBody();

           runApi.send(url,method,header,body);
        });


        window.ResponseEditor = CodeMirror.fromTextArea(document.getElementById('responseBodyContainer'),{
            lineNumbers: true,
            mode: "text/html",
            readOnly : true,
            lineWrapping : true
        })

    });
</script>
@endsection