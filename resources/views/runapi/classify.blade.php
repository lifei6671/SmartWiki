@if($parent_id == 0)
<li data-id="{{$classify_id}}">
    <a href="###" title="{{$classify_name}}">
        <i class="fa fa-folder"></i>
        <div class="tool-api-menu-title">{{$classify_name}}<br/><span class="text">{{$api_count}} 个接口</span></div>
    </a>
    <div class="btn-group btn-group-more">
        <button class="btn btn-more dropdown-toggle" style="height: 63px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-h"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-more">

            <li><a href="###" title="添加" class="btn_classify_add"><i class="fa fa-folder"></i> 添加</a> </li>
            @if(isset($role) && $role === 0)
            <li><a href="###" title="编辑" class="btn_classify_edit"><i class="fa fa-pencil"></i> 编辑</a></li>
            <li><a href="###" title="共享" class="btn_classify_share"><i class="fa fa-share-alt-square"></i> 共享</a> </li>
            <li><a href="###" title="删除" class="btn_classify_del"><i class="fa fa-trash"></i> 删除</a></li>
            @endif

        </ul>
    </div>
    <ul class="tool-api-menu-submenu"></ul>
    <ul class="api-items"></ul>
</li>
@else
    <li  data-id="{{$classify_id}}">
        <a href="###" title="{{$classify_name}}"><i class="fa fa-folder-o"></i>
            {{$classify_name}}
        </a>
        <div class="btn-group btn-group-more">
            <button class="btn btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-more">
                <li><a href="###" title="编辑" class="btn_classify_edit"><i class="fa fa-pencil"></i> 编辑</a></li>
                @if(isset($role) && $role === 0)
                <li><a href="###" title="删除" class="btn_classify_del"><i class="fa fa-trash"></i> 删除{{$role}}</a></li>
                @endif
            </ul>
        </div>
        <ul class="api-items"> </ul>
    </li>
@endif