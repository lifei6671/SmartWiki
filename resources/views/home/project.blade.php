@extends('home')
@section('title'){{$title}}@endsection
@section('content')
    <div class="container smart-container manual-project">
        <div class="manual-project-box">
            <div class="pull-left">
                <div class="manual-project-box-img">
                    <img src="{{asset('static/images/project_default.png')}}" width="50">
                </div>
                <div class="manual-project-box-title">
                    {{$title}}
                </div>
                <div class="author">
                   <img src="{{$author_headimgurl}}" width="30" class="img-circle"> <span class="author-name">{{$author}}</span>
                    @if(empty($project->modify_time)=== false)
                    <span class="modify-time">最后更新时间 {!! date('Y年m月d日',strtotime($project->modify_time)) !!}</span>
                    @elseif(empty($project->create_time) === false)
                        <span class="modify-time">最后更新时间 {!! date('Y年m月d日',strtotime($project->create_time)) !!}</span>
                    @endif
                </div>
                <div class="manual-action">
                    @if(empty($first_document))
                        <a href="javascript:;" class="btn btn-warning">暂未发布文档</a>
                    @else
                        <a href="{{route('document.show',['id' => $first_document->doc_id])}}" class="btn btn-success">阅读</a>
                    @endif
                </div>
            </div>
            <div class="pull-right hidden-xs">
                <div class="manual-project-box-qrcode">
                    <img src="{{route('qrcode.index',['id'=>$project->project_id])}}">
                    <div class="text-center">扫一扫用手机阅读</div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="manual-project-body">
            <div class="tab-head">
                <a href="javascript:;" class="tab-item active" data-target="#tab-description">概要</a>
                <a href="javascript:;" class="tab-item" data-target="#tab-catalog">目录</a>
                @if(empty($records) === false)
                <a href="javascript:;" class="tab-item" data-target="#tab-records">更新记录</a>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="tab-content">
                <div class="tab-item active" id="tab-description">
                    {{$body}}
                </div>
                <div class="tab-item" id="tab-catalog">
                    {!! $tree !!}
                </div>
                @if(empty($records) === false)
                    <div class="tab-item" id="tab-records">

                    </div>
                @endif
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    @endsection

    @section('modelDialog')

    @endsection

@section('scripts')
<script type="text/javascript">
$(function () {
   $(".tab-head>.tab-item").on('click',function () {
       $(this).closest('.tab-head').children('.tab-item').removeClass('active');
       $(this).addClass('active');
       $(".tab-content>.tab-item").removeClass('active');
       var target = $(this).attr('data-target');
       $(target).addClass('active');
   }) ;
});

</script>
@endsection