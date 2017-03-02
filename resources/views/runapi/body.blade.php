<input type="hidden" id="isChangeForApi" value="0">
<input type="hidden" name="http_method" value="{{$method or 'GET'}}">
@include('runapi.metadata',['isForm' => false,'isDisplayClassify' => !isset($api_id),'apiId' => isset($api_id)?$api_id:0,'api_name' => isset($api_name) ? $api_name : '','description' => isset($description) ? $description : '','classify_id' => isset($classify_id)?$classify_id:'','classify_name' => isset($classify_name)?$classify_name:''])

<div class="tool-api-title">
    <h4 class="title"><i class="fa fa-circle saved"></i> <span>{{$api_name or '无标题'}}</span></h4>
    <div class="text">{{$description or '暂无描述'}}</div>
</div>
<div class="tool-api-method">
    <div class="row">
        <div class="col-lg-9 col-sm-8 col-xs-7">
            <div class="input-group">
                <div class="input-group-btn" id="btn-http-group">
                    <button type="button" id="httpMethod" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px;">{{$method or 'GET'}} <span class="caret"></span></button>
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
                <input type="text" class="form-control" name="request_url" id="requestUrl" aria-label="..." placeholder="请输入一个的URL" value="{{$request_url or ''}}">
            </div><!-- /input-group -->
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-5">
            <button type="button" id="sendRequest" class="btn btn-primary" style="width: 70px" data-loading-text="发送中"> 发 送</button>

            <div class="btn-group">
                <button class="btn btn-default" style="width: 70px" id="btnSaveApi" type="submit"{!! isset($api_id) ? 'disabled' : '' !!} data-loading-text="保存中">
                    保 存
                </button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="###" id="editAndSave">编辑并保存</a> </li>
                    {{--<li><a href="###" id="saveToDocument">保存到文档</a></li>--}}
                    <li><a href="###" id="makeMarkdown">生成 Markdown</a> </li>
                </ul>
            </div>

        </div>
    </div>
</div>
<div class="row tool-api-parameter">
    <ul class="nav nav-tabs" id="parameter-tab">
        <li role="presentation" class="active"  href="#headers"><a href="###">Headers</a></li>
        <li role="presentation"  href="#body"><a href="###">Body</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="headers">
            <table style="margin-top: 10px;width: 100%" class="parameter-active">
                <tbody>
                @if(empty($headers) === false)
                    @foreach($headers as $item)
                        @include("runapi.params",$item)
                    @endforeach
                @endif
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
                <li href="#x-www-form-urlencodeed"><label><input type="radio" name="parameterType" value="x-www-form-urlencodeed"{{(!isset($enctype) || $enctype == 'x-www-form-urlencodeed') ? ' checked' : ''}}>x-www-form-urlencodeed</label></li>
                <li href="#raw"><label><input type="radio" name="parameterType" value="raw"{{(isset($enctype) && $enctype == 'raw') ? ' checked' : ''}}>raw</label></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane {{((isset($enctype) && $enctype == 'x-www-form-urlencodeed') || empty($enctype)) ? ' active' : ''}}" id="x-www-form-urlencodeed">
                    <table style="margin-top: 10px;width: 100%" class="parameter-active">
                        <tbody>
                        <tr>
                            @if(empty($body) === false)
                                @foreach($body as $item)
                                    @include("runapi.params", $item)
                                @endforeach
                            @endif
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
                <div role="tabpanel" class="tab-pane{{(isset($enctype) && $enctype == 'raw') ? ' active' : ''}}" id="raw">
                    <textarea id="rawModeData">{!! $raw_data or '' !!}</textarea>
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
            <table class="table table-condensed" style="table-layout:fixed;">
                <thead>
                <tr><th>Name</th><th style="word-wrap:break-word;">Value</th><th>Domian</th><th>Path</th><th>Expires</th><th>HTTP</th><th>Secure</th></tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div role="tabpanel" class="tab-pane" id="responseHeader">

        </div>
    </div>
</div>
