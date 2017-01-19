<div class="btn-group dropdown-menu-right pull-right slidebar visible-xs-inline-block">
    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-align-justify"></i></button>
    <ul class="dropdown-menu" role="menu">
        @if(isset($member))
            <li>
                <a href="{{route('account.logout')}}" title="退出登录">
                    <i class="fa fa-sign-out"></i> 退出登录
                </a>
            </li>
            <li>
                <a href="{{route('member.projects')}}" class="img" title="个人中心">
                    <i class="fa fa-user"></i> 个人中心
                </a>
            </li>
        @else
            <li>
                <a href="{{route('account.login')}}" title="用户登录">登录</a>
            </li>
        @endif
    </ul>
</div>