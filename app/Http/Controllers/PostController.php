<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function new()
    {
        return view('posts.create');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auth = Auth::user();

        $posts = Post::where('archived', false)
            ->where(function ($query) use ($auth) {
                // Show public posts to everyone
                $query->where('privacy', 0);

                if ($auth) {
                    // Find mutual followers (friends)
                    $mutualIds = DB::table('follows as f1')
                        ->join('follows as f2', 'f1.following_id', '=', 'f2.follower_id')
                        ->where('f1.follower_id', $auth->id)
                        ->where('f2.following_id', $auth->id)
                        ->pluck('f1.following_id');

                    // Include posts from mutual friends or yourself
                    $query->orWhereIn('uploader', $mutualIds)
                        ->orWhere('uploader', $auth->id);
                }
            })
            ->orderByDesc('created_at')
            ->get();

        $likes = Like::all();

        return view('posts.index', compact('posts', 'likes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file_dir = 'images/posts/';

        $request->validate([
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:png,jpg,jpeg|max:10240',
        ]);

        $post = new Post;
        $post->uploader = Auth::user()->id;
        $post->caption = $request->caption;
        $post->description = $request->description;
        $post->save();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $file_name = $file->getClientOriginalName();
                $file_size = $file->getSize();
                $file_path = $file_dir.$file_name;

                $file->move($file_dir, $file_name);

                Attachment::create([
                    'user_id' => Auth::id(),
                    'post_id' => $post->id,
                    'file_name' => $file_name,
                    'file_location' => $file_path,
                    'file_size' => $file_size,
                ]);
            }
        }

        return redirect()->route('posts');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        // Separate creator's and others' comments
        $creatorComments = $post->comments()
            ->where('user_id', $post->uploader)
            ->with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->orderBy('created_at', 'asc') // oldest first
            ->get();

        $otherComments = $post->comments()
            ->where('user_id', '!=', $post->uploader)
            ->with(['user', 'likes', 'replies.user', 'replies.likes'])
            ->orderBy('created_at', 'desc') // newest first
            ->get();

        // Merge the two collections (creator first)
        $post->setRelation('comments', $creatorComments->concat($otherComments));

        $likes = Like::where('post_id', $id)->get();

        return view('posts.show', compact('post', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function toggle(Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        $count = $post->likes()->count();

        return response()->json([
            'liked' => $liked,
            'count' => $count,
        ]);
    }

    public function search()
    {
        return view('messages.index');
    }
}
