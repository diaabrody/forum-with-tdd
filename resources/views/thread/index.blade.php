@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($threads as $thread)
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
{{--                                <span style="margin: 2px"> by:  {{ $thread->creator->name }}</span>--}}

                                <h4 class="flex">
                                    <a href="{{ $thread->path() }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>

                                <strong> {{$thread->replies_count}}
                                    {{Str::plural('reply' ,$thread->replies_count)}}
                                </strong>
                            </div>
                        </div>
                        <div class="body">{{ $thread->body }}</div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
@endsection
