<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('passenger-booking.{passengerId}', function ($user, $passengerId) {
//     return (int) $user->id === (int) $passengerId;
// });
