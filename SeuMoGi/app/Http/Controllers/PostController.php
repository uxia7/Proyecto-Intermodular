<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Timeline: posts propios + de gente a la que sigo
        $user = $request->user();

        $followingIds = $user->following()->pluck('users.id');

        $posts = Post::with('user')
            ->whereIn('user_id', $followingIds->push($user->id))
            ->latest()
            ->paginate(20);

        return response()->json($posts);

        foreach ($posts as $post) {
            $post->user->is_following = auth()->user()->isFollowing($post->user->id);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'        => 'nullable|in:post,theory,music',
            'content'     => 'required|string',
            'image_url'   => 'nullable|string',
            'song_title'  => 'nullable|string',
            'song_artist' => 'nullable|string',
            'song_url'    => 'nullable|string',
        ]);

        $data['type']    = $data['type'] ?? 'post';
        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        $post->load('user');
        return response()->json($post);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // Filtrar teorías
    public function theories()
    {
        $posts = Post::with('user')
            ->where('type', 'theory')
            ->latest()
            ->paginate(20);

        return response()->json($posts);
    }

    // Filtrar música
    public function music()
    {
        $posts = Post::with('user')
            ->where('type', 'music')
            ->latest()
            ->paginate(20);

        return response()->json($posts);
    }

    public function react(Request $request, $id)
    {
        $request->validate([
            'reaction' => 'required|in:heart,fire,cry,mindblown'
        ]);

        $like = \App\Models\Like::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'post_id' => $id
            ],
            [
                'reaction' => $request->reaction
            ]
        );

        return response()->json(['message' => 'Reacción guardada']);
    }

    public function reactions($id)
    {
        $reactions = \App\Models\Like::where('post_id', $id)->get();

        return response()->json($reactions);
    }

    public function comments($id)
    {
        return \App\Models\Comment::with('user')
            ->where('post_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function addComment(Request $request, $id)
    {
        $request->validate(['content' => 'required']);

        $comment = \App\Models\Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $id,
            'content' => $request->content
        ]);

        return response()->json($comment);
    }
}
