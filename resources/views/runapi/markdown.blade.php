<!-- Modal -->
<div class="modal fade" id="makeMarkdownModal" tabindex="-1" role="dialog" aria-labelledby="makeMarkdownLabel">
    <div class="modal-dialog" role="document" style="width: 90%;height: 100%;">
        <div class="modal-content">
            <form method="post" id="makeMarkdownForm" action="{{route("runapi.markdown")}}">
            <div class="modal-header">
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                <h4 class="modal-title" id="myModalLabel">生成Markdown</h4>
            </div>
            <div class="modal-body" style="padding: 0;margin: 0;">
                <div id="editormdContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
            </form>
        </div>
    </div>
</div>