<li>
    <a href="{{route('home.show',array('id'=>$project_id))}}" class="box">
        <div class="pull-left imgbox">
            <i class="fa fa-desktop"></i>
        </div>
        <h4>{{$project_name}}</h4>
        <span>共{{$doc_count}}个文档</span>
    </a>
    <p class="summary">
        <a href="{{route('home.show',array('id'=>$project_id))}}" class="text">
            {{$description}}
        </a>
    </p>
</li>