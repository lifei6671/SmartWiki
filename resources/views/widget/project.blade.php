<li>
    <a href="{{route('home.show',array('id'=>$project_id))}}" class="box" title="{{$project_name}}" target="_blank">
        <div class="pull-left imgbox">
            <i class="fa fa-desktop"></i>
        </div>
        <h4>{{$project_name}}</h4>
        <span>共{{$doc_count}}个文档</span>
    </a>
    <p class="summary hidden-xs hidden-sm hidden-md">
        <a href="{{route('home.show',array('id'=>$project_id))}}" class="text" target="_blank">
            {{$description}}
        </a>
    </p>
</li>