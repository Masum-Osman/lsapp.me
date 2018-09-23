@extends('layouts.app')

@section('content')
<h1>{{$title}}</h1>
        <h1>Welcome to Laravel</h1>
        <h1> This is Service page</h1>
        <p>This is laravel application about page</p>

        <ul>
        @if(count($services) > 0)
                <ul class="list-group">
                @foreach($services as $service)
                        <li class="list-group-item">{{$service}}</li>
                @endforeach
        </ul>
        @endif
 @endsection 
