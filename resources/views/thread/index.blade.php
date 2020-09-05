@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
              @include('thread._list')
                {{$threads->render()}}
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Trending List
                    </div>
                    <div class="body">
                        <ul class="list-group">
                            @foreach($trending_threads as $hread)
                                 <li class="list-group-item"><a href="{{$hread->path}}">{{$hread->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection
