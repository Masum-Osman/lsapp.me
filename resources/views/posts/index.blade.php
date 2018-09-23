@extends('layouts.app')

@section('content')
    <h1>Posts: </h1>
@if(count($posts) > 0)
@foreach($posts as $post)
    <div class="well">
        <div class = "row">
            <div class = "col-md-4 col-sm-4">
                       
                <!--
                    1. @@if($post->cover_image != 'noImage.jpg'){
                    2. @@if(hasFile('cover_image')){
                <img style = "width:100%" src = "/storage/cover_image/{{$post->cover_image}}">
            }
            @@endif

            you should do that in future. 
        -->

           
                    <img style = "width:100%" src = "/storage/cover_image/{{$post->cover_image}}">
                
                
            </div>
            <div class = "col-md-8 col-sm-8">
                <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                <small>Written On {{$post->created_at}}</small>
            </div>
        </div>   
    </div>
@endforeach
    {{$posts->links()}}
@else
<p>No Posts found</p>
@endif
@endsection

; <a href = "/posts/{{$post->id}}"></a>