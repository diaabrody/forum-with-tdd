<?php

namespace App\Providers;

use App\Events\ThreadHasNewReply;
use App\Listeners\NotifyMentionedUser;
use App\Listeners\NotifyUserNewReplay;
use App\Notifications\MentionedUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ThreadHasNewReply::class=>[
            NotifyUserNewReplay::class ,
            NotifyMentionedUser::class

        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
