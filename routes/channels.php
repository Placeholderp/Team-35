<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|This file is used to register all of the event broadcasting channels.
|
In this example, the 'App.Models.User.{id}' channel is defined so that a user can only
 * listen to channels corresponding to their own user ID.

*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
