<div class="team-member-item" data-account="{{$account or ''}}">
    <div class="pull-left card"><i class="fa fa-vcard-o"></i></div>
    <div class="pull-right" style="width: 235px;">
        <div>
            <div class="pull-left title">{{$account or ''}}</div>
            @if($role !== 0)
                <div class="pull-right close" title="删除">
                    <i class="fa fa-times"></i>
                </div>
            @endif
            <div class="clearfix"></div>
        </div>
        <div style="font-size: 12px;color: #A599A8">
            time: {{$create_time or ''}}
        </div>
    </div>
</div>