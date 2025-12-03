<?php

namespace App\Http\Controllers;

use App\Models\User;

class NotificationController extends Controller
{
    public function getNotifications($userId)
    {
        $user = User::find($userId);

        if (!$user) return response()->json([], 404);

        return response()->json($user->notifications);
    }

    
}

