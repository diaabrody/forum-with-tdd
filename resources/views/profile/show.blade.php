@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @forelse ($activities as $date=>$activityDate)
            <h3 class="pb-2 mt-4 mb-2 border-bottom">{{$date}}</h3>
            @foreach($activityDate as $activity )
                @if(view()->exists("profile.activities.{$activity->type}"))
                    @include("profile.activities.{$activity->type}" , [
                     'profileUser'=>$profileUser ,
                         'activity'=>$activity
                      ])
                    <hr>
                @endif
            @endforeach
            @empty
                <p>Not Activity Found</p>
        @endforelse
    </div>
@endsection
