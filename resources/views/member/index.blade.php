@extends('member')
@section('title')个人资料@endsection
@section('styles')
<link href="{{asset('static/webuploader/webuploader.css')}}" rel="stylesheet">
<link href="{{asset('static/cropper/cropper.css')}}" rel="stylesheet">
    <style type="text/css">
        #upload-logo-panel .wraper{
            float: left;
            background: #f6f6f6;
            position: relative;
            width: 360px;
            height: 360px;
            overflow: hidden;
        }
        #upload-logo-panel .watch-crop-list{
            width: 170px;
            padding:10px 20px;
            margin-left: 10px;
            background-color: #f6f6f6;
            text-align: center;
            float: right;
            height: 360px;
        }
        #image-wraper{
            text-align: center;
        }
        .webuploader-pick{

        }
        .webuploader-pick-hover{

        }
        .webuploader-container{
            padding: 0;
            border: 0;
            height: 40px;
        }
        .watch-crop-list>ul{
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .webuploader-container div{
            width: 77px !important;
            height: 40px !important;
            left: 0 !important;
        }
        .img-preview {
            margin: 5px auto 10px auto;
            text-align: center;
            overflow: hidden;
        }
        .img-preview > img {
            max-width: 100%;
        }
        .preview-lg{
            width: 120px;
            height: 120px;
        }
        .preview-sm{
            width: 60px;
            height: 60px;
        }
        #error-message{
            font-size: 13px;
            color: red;
            vertical-align: middle;
            margin-top: -10px;
            display: inline-block;
            height: 40px;
        }
    </style>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('static/cropper/cropper.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/webuploader/webuploader.js')}}"></script>

    <script type="text/javascript">
        function showError($msg) {
            $("#form-error-message").addClass("error-message").removeClass("success-message").text($msg);
            return false;
        }
        function showSuccess($msg) {
            $("#form-error-message").addClass("success-message").removeClass("error-message").text($msg);
            return true;
        }

    $(function () {
        var modalHtml = $("#upload-logo-panel").find(".modal-body").html();

       $("#upload-logo-panel").on("hidden.bs.modal",function () {
           $("#upload-logo-panel").find(".modal-body").html(modalHtml);
       });

        $("#basic-form").ajaxForm({
            beforeSubmit : function () {

                var email = $.trim($("#user-email").val());
                if(!email){
                    return showError('邮箱不能为空');
                }
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
    try {
        var uploader = WebUploader.create({
            auto: false,
            swf: '{{asset('static/webuploader/Uploader.swf')}}',
            server: '{{route('member.upload')}}',
            pick: "#filePicker",
            fileVal : "image-file",
            fileNumLimit : 1,
            compress : false,
            accept: {
                title: 'Images',
                extensions: 'jpg,jpeg,png',
                mimeTypes: 'image/jpg,image/jpeg,image/png'
            }
        }).on("beforeFileQueued",function (file) {
            uploader.reset();
        }).on( 'fileQueued', function( file ) {
            uploader.makeThumb( file, function( error, src ) {
                $img = '<img src="' + src +'" style="max-width: 360px;max-height: 360px;">';
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $("#image-wraper").html($img);
                window.ImageCropper = $('#image-wraper>img').cropper({
                    aspectRatio: 1 / 1,
                    dragMode : 'move',
                    viewMode : 1,
                    preview : ".img-preview"
                });
            }, 1, 1 );
        }).on("uploadError",function (file,reason) {
            console.log(reason);
            $("#error-message").text("上传失败:" + reason);

        }).on("uploadSuccess",function (file, res) {

            if(res.success == 1){
                console.log(res);
                $("#upload-logo-panel").modal('hide');
                $("#headimgurl").attr('src',res.url);
            }else{
                $("#error-message").text(res.message);
            }
        }).on("beforeFileQueued",function (file) {
            if(file.size > 1024*1024*2){
                uploader.removeFile(file);
                uploader.reset();
                alert("文件必须小于2MB");
                return false;
            }
        }).on("uploadComplete",function () {
            $("#saveImage").button('reset');
        });
        $("#saveImage").on("click",function () {
            var files = uploader.getFiles();
            if(files.length > 0) {
                $("#saveImage").button('loading');
                var cropper = window.ImageCropper.cropper("getData");

                uploader.option("formData", cropper);

                uploader.upload();
            }else{
                alert("请选择头像");
            }
        });
    }catch(e){
        console.log(e);
    }

    </script>
@endsection
@section('content')
    <div class="member-box">
        <div class="box-head">
            <h4>个人资料</h4>
        </div>
        <div class="box-body">
            <div class="form-left">
                <form role="form" method="post" action="{{route('member.index')}}" id="basic-form">
                    <div class="form-group">
                        <label for="user-account">用户名</label>
                        <input type="text" class="form-control disabled" name="userAccount" value="{{$member->account}}" disabled id="user-account" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="user-nickname">昵称</label>
                        <input type="text" class="form-control" name="userNickname" id="user-nickname" max="20" placeholder="昵称" value="{{$member->nickname or ''}}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="user-email">邮箱<strong class="text-danger">*</strong></label>
                        <input type="email" class="form-control" value="{{$member->email or ''}}" id="user-email" name="userEmail" max="100" placeholder="邮箱" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>手机号</label>
                        <input type="text" class="form-control" id="user-phone" name="userPhone" maxlength="20" title="手机号码" placeholder="手机号码" value="{{$member->phone}}" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label class="description">描述</label>
                        <textarea class="form-control" rows="3" title="描述" name="description" id="description" maxlength="500" autocomplete="off">{{$member->description or ''}}</textarea>
                        <p style="color: #999;font-size: 12px;">描述不能超过500字</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" data-loading-text="保存中...">保存修改</button>
                        <span id="form-error-message" class="error-message"></span>
                    </div>
                </form>
            </div>
            <div class="form-right">
                <label>
                    <a href="javascript:;" data-toggle="modal" data-target="#upload-logo-panel">
                        <img src="{{$member->headimgurl}}" onerror="this.src='{{asset('static/images/middle.gif')}}'" class="img-circle" alt="头像" style="max-width: 120px;max-height: 120px;" id="headimgurl">
                    </a>
                </label>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="upload-logo-panel" tabindex="-1" role="dialog" aria-labelledby="修改头像" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">修改头像</h4>
                </div>
                <div class="modal-body">
                    <div class="wraper">
                        <div id="image-wraper">

                        </div>
                    </div>
                    <div class="watch-crop-list">
                        <div class="preview-title">预览</div>
                        <ul>
                            <li>
                                <div class="img-preview preview-lg"></div>
                            </li>
                            <li>
                                <div class="img-preview preview-sm"></div>
                            </li>
                        </ul>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div class="modal-footer">
                    <span id="error-message"></span>
                    <div id="filePicker" class="btn">选择</div>
                    <button type="button" id="saveImage" class="btn btn-success" style="height: 40px;width: 77px;" data-loading-text="上传中...">上传</button>
                </div>
            </div>
        </div>
    </div>
@endsection