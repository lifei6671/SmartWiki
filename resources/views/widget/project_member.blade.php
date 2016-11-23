<div class="row user-item">
    <div class="col-sm-5">
        <a href="{{route('member.users.edit',['id'=>$item->member_id])}}" class="pull-left"><img src="{{$item->headimgurl}}" class="img-circle" style="width: 36px;" onerror="this.src='/static/images/middle.gif'"> </a>
        <div class="user-text user-info">
            <strong class="pull-left">{{$item->account}}&nbsp;</strong>
            <p class="pull-left">&nbsp;{{$item->email}}</p>
        </div>
    </div>
    <div class="col-sm-3">
        @if($item->role_type == 0)
            <span class="label label-primary">参与者</span>
        @else
            <span class="label label-success">拥有者</span>
        @endif

    </div>
    <div class="col-sm-4 text-right">
        <button class="btn btn-danger btn-sm delete-btn"{{$item->role_type == 1 ? ' disabled':''}} data-id="{{$item->account}}" data-loading-text="处理中...">
            <i class="fa fa-close"></i>
            踢出项目
        </button>
    </div>
</div>