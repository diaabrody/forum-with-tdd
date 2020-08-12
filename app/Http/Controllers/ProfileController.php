<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function show(User $user)
    {
        return view('profile.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function getActivities(User $user): Collection
    {
        return $user->activities()
            ->latest()
            ->with('subject')
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('j F Y');
            });
    }
}
