@foreach ($threads as $thread)
    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                           {{ $thread->title }}
                        </a>
                    </h4>
                    <h5>
                        Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                    </h5>
                </div>
                <strong> {{$thread->replies_count}}
                    {{Str::plural('reply' ,$thread->replies_count)}}
                </strong>
            </div>
        </div>
        <div class="body">{{ $thread->body }}</div>
    </div>
    <hr>
@endforeach
