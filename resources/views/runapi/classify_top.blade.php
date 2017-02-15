<li>
    <a href="###" title="{{$classify_name}}">
        <i class="fa fa-folder"></i>
        <div class="tool-api-menu-title">{{$classify_name}}<br/><span class="text">{{$api_count}} 个接口</span></div>
    </a>
    <div class="btn-group btn-group-more">
        <button class="btn btn-more dropdown-toggle" style="height: 63px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-h"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-more" data-id="{{$classify_id}}" data-parent-id="{{$parent_id}}" data-title="{{$classify_name}}">
            <li><a href="###" title="编辑" class="btn_classify_edit"><i class="fa fa-pencil"></i> 编辑</a></li>
            <li><a href="###" title="添加分类" class="btn_classify_add"><i class="fa fa-folder"></i> 添加分类</a> </li>
            <li><a href="###" title="删除" class="btn_classify_del"><i class="fa fa-trash"></i> 删除</a></li>
        </ul>
    </div>
</li>