@if(isset($isForm))
    <?php $beginForm = '<form method="post" id="editApiForm" action="'.route("runapi.edit.api").'">';?>
    <?php $endForm = '</form>';?>
@endif

<div class="modal fade" id="{{isset($isForm) ? 'editApiModal' : 'saveApiModal'}}" tabindex="-1" role="dialog" aria-labelledby="editApiTitle">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! $beginForm or '' !!}
                <input type="hidden" name="apiId" value="{{$apiId or ''}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editClassifyTitle">{{isset($isForm) ? '编辑接口' : '保存接口'}}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="classifyName">接口名称</label>
                        <input type="text" name="apiName" class="form-control" value="{{$api_name or ''}}" placeholder="接口名称" id="apiName" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="description">描述</label>
                        <textarea name="apiDescription" class="form-control" style="resize: none;height: 150px;" placeholder="接口功能简单描述" id="apiDescription" autocomplete="off">{{$description or ''}}</textarea>
                    </div>
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
                            <div class="dropdown-select-menu">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="保存中">保存</button>
                </div>
            {!! $endForm or '' !!}
        </div>
    </div>
</div>