<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Save;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function toggle(Blog $blog)
    {
        $save = Save::where('user_id', auth()->id())->where('blog_id', $blog->id)->first();
        if ($save) {
            $save->delete();
            $status = 'unsaved';
        } else {
            Save::create([
                'user_id' => auth()->id(),
                'blog_id' => $blog->id
            ]);
            $status = 'saved';
        }

        return response()->json([
            'status' => $status
        ]);
    }
}
