<?php

namespace App\Listeners;

use App\Events\WelcomeUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendWelcomeMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WelcomeUser  $event
     * @return void
     */
    public function handle(WelcomeUser $event)
    {
        // Send the welcome email to the user
        
        $user = $event->user;
        
        $data = [
        	   'name' => $user->name,
        	   'password' => $user->password,        	
        	];
        
        Mail::send('emails.welcome', $data, function($message) use($user) {
                 $message
        	    ->from(config('mail.from.address'), config('mail.from.name'))
        	    ->to($user->email, '')
        	    ->subject('Welcome '.$user->name.' to CC-API');
        });
    }
}
