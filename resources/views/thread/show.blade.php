@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="/vendor/atjs/css/jquery.atwho.min.css">
@endsection
@section('content')
<thread-view inline-template :replies_count ="{{$thread->replies_count}}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="/profiles/{{ $thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </span>
                            @can('delete' , $thread)
                                <form action="{{$thread->path()}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link">Delete</button>
                                </form>
                            @endcan

                        </div>
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>
                <replies  @removed="remove"></replies>

                {{--                {{$replies->links()}}--}}
            </div>

            <div class="col-md-4">
                <div class="card" style="margin-bottom:10px ; margin-top: 10px">

                    <div class="card-body">
                        <p>
                            This thread was published {{$thread->created_at->diffForHumans()}}
                            <a href="/profiles/{{ $thread->creator->name}}">{{ $thread->creator->name }}</a>,
                            and currently
                            has
                            <span v-text="repliesCounts"></span>
                        </p>
                        <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection

