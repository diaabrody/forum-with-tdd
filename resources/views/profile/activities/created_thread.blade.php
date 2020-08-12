<x-activity>
    <x-slot name="heading">
        {{ $profileUser->name }} publish thread
        <a href="{{$activity->subject->path()}}">{{$activity->subject->title}}</a>
    </x-slot>
    <x-slot name="body">
        {{$activity->subject->body}}
    </x-slot>
</x-activity>
