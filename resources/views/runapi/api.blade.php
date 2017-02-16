<li data-id="{{$api_id}}">
    <a href="javascript:;" title="{{$api_name}}">
        <i class="fa"></i>
        <span class="method-default method-get">{{$method}}</span>
        <span class="menu-title">{{$api_name}}</span>
    </a>
    <div class="btn-group btn-group-more">
        <button class="btn btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-h"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-more">
            <li><a href="###" title="编辑接口" class="btn_api_edit"><i class="fa fa-pencil"></i> 编辑</a></li>
            <li><a href="###" title="删除接口" class="btn_api_del"><i class="fa fa-trash"></i> 删除</a></li>
        </ul>
    </div>
</li>