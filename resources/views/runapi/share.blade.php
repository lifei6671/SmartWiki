<div class="modal fade" id="shareRequestFolderModal" tabindex="-1" role="dialog" aria-labelledby="shareRequestFolderModalTitle">
    <div class="modal-dialog" role="document" style="width: 620px;">
        <div class="modal-content">
            <form method="post" id="shareRequestFolderForm" action="{{route('runapi.share.api')}}">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editClassifyTitle">共享分类</h4>
            </div>
            <div class="modal-body">
                <div class="modal-body-content">
                    @if(!isset($errcode))
                    <input type="hidden" name="classify_id" value="{{$classify_id or ''}}">
                    <div class="form-group">
                        <div class="col-lg-2">
                            <label for="memberName">用户账号</label>
                        </div>
                        <div class="col-lg-5">
                            <input type="text" name="account" id="memberName" class="form-control">
                        </div>
                        <div class="col-lg-5">
                            <button type="submit" class="btn btn-success btn-sm" data-loading-text="提交中">
                                添加
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <hr>
                    <div class="form-group team-member-list" style="padding: 5px 0 5px 5px;">
                        @if(isset($lists) && empty($lists) === false)
                            @foreach($lists as $item)
                                @include('runapi.shareitem',(array)$item)
                            @endforeach
                        @endif
                        <div class="clearfix"></div>
                    </div>
                    @else
                        {{$message}}
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary" data-loading-text="保存中">保存</button>
            </div>
            </form>
        </div>
    </div>
</div>