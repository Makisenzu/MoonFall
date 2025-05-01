<?php

use App\Models\Volunteer;
use Laravel\Reverb\Loggers\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::routes(['middleware' => ['web', 'auth']]);
Broadcast::channel('news.alert.{audience}', function ($user, $audience) {
    try {
        $isVolunteer = Volunteer::where('user_id', $user->id)->exists();

        if (strtolower($audience) === 'volunteer') {
            return $isVolunteer;
        }

        if (strtolower($audience) === 'civilian') {
            return !$isVolunteer;
        }

        return false;
    } catch (\Exception $e) {
        Log::error('Broadcast authorization error: ' . $e->getMessage());
        return false;
    }
});

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });