<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Blog $blog)
    {
        $like = Like::where('user_id', auth()->id())->where('blog_id', $blog->id)->first();
        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'blog_id' => $blog->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'count' => $blog->likes()->count()
        ]);
    }
}
