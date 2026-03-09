<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request, $id)
    {
        $user = $request->user();

        if ($user->id == $id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        $target = User::findOrFail($id);

        Follow::firstOrCreate([
            'follower_id' => $user->id,
            'followed_id' => $target->id,
        ]);

        return response()->json(['message' => 'Now following']);
    }

    public function unfollow(Request $request, $id)
    {
        $user = $request->user();

        Follow::where('follower_id', $user->id)
            ->where('followed_id', $id)
            ->delete();

        return response()->json(['message' => 'Unfollowed']);
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user->followers);
    }

    public function following($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user->following);
    }
}
