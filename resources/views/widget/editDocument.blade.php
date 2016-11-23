<form class="form-horizontal" role="form" method="post" onsubmit="{{ $method }}(this);return false;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="modal-title">{{  $title or '添加文件' }}</h4>
    </div>
    <div class="modal-body">
         <div class="form-group">
             <label for="documentName" class="col-sm-2 control-label">{{$inputTitle}}</label>
             <div class="col-sm-10">
                 <input type="text" name="documentName" class="form-control" id="documentName" placeholder="文档名称">
             </div>
         </div>
        @if($type == 1)
            <div class="form-group">
                 <label for="inputPassword3" class="col-sm-2 control-label">文档描述</label>
                 <div class="col-sm-10">
                     <textarea class="form-control" rows="3"  name="summary" title="文档描述"></textarea>
                 </div>
             </div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary">确定</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>

    </div>
</form>