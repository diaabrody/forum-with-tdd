<x-activity>
    <x-slot name="heading">
        <a href="{{$activity->subject->favorited->path()}}">
            {{ $profileUser->name }}
            liked  reply
        </a>

    </x-slot>
    <x-slot name="body">
        {{$activity->subject->favorited->body}}
    </x-slot>
</x-activity>
