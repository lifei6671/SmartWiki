<div class="modal fade" id="shareApiForm" tabindex="-1" role="dialog" aria-labelledby="shareApiFormTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="editApiForm" action="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editClassifyTitle">共享分类</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="apiId" value="{{$api_id or ''}}">
                <div class="form-group">
                    <label for="classifyName">接口名称</label>
                    <input type="text" name="apiName" class="form-control" value="{{$api_name or ''}}" placeholder="接口名称" id="apiName" autocomplete="off" maxlength="50">
                </div>
                <div class="form-group">
                    <label for="description">描述</label>
                    <textarea name="apiDescription" class="form-control" style="resize: none;height: 150px;" placeholder="接口功能简单描述" id="apiDescription" autocomplete="off" maxlength="2000">{{$description or ''}}</textarea>
                </div>
                @if(isset($isDisplayClassify) && $isDisplayClassify === true)

                    <div class="form-group">
                        <label for="apiClassifyId">所属分类</label>
                        <div class="input-group dropdown-select" style="width: 60%">
                            <input type="text" class="form-control" placeholder="选择接口分类" value="{{$classify_name or ''}}">
                            <input type="hidden" name="classifyId" value="{{$classify_id or ''}}">
                            <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <span class="caret"></span>
                                    </button>
                                </span>
                            <div class="dropdown-select-menu"></div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary" data-loading-text="保存中">保存</button>
            </div>
            </form>
        </div>
    </div>
</div>