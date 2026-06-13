<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'total_views' => Blog::sum('views'),
            'total_likes' => \App\Models\Like::count(),
        ];

        // For graph: Last 7 days blogs
        $graphData = Blog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'graphData'));
    }

    public function blogs(Request $request)
    {
        $query = Blog::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $blogs = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.blogs.partials.table-rows', compact('blogs'))->render(),
                'pagination' => $blogs->links()->toHtml(),
            ]);
        }

        return view('admin.blogs.index', compact('blogs'));
    }

    public function createBlog()
    {
        return view('admin.blogs.create');
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string|in:PDF Tools,AI Tools,Business,Comparisons',
            'affiliate_link' => 'nullable|url|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image' => $imagePath,
            'user_id' => auth()->id(),
            'is_published' => true,
            'category' => $request->category,
            'affiliate_link' => $request->affiliate_link,
        ]);

        return redirect()->route('admin.blogs')->with('success', 'Blog created successfully.');
    }

    public function editBlog(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string|in:PDF Tools,AI Tools,Business,Comparisons',
            'affiliate_link' => 'nullable|url|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
            $blog->image = $imagePath;
        }

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->category = $request->category;
        $blog->affiliate_link = $request->affiliate_link;
        $blog->save();

        return redirect()->route('admin.blogs')->with('success', 'Blog updated successfully.');
    }

    public function deleteBlog(Blog $blog)
    {
        $blog->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => 'Blog deleted successfully.']);
        }

        return redirect()->route('admin.blogs')->with('success', 'Blog deleted successfully.');
    }
}
