<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('user')->where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        $blogs = $query->latest()->paginate(12)->withQueryString();

        // Return JSON partial for AJAX requests
        if ($request->ajax()) {
            $html = view('partials.blog-cards', compact('blogs'))->render();
            $pagination = $blogs->links()->toHtml();
            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'total'      => $blogs->total(),
            ]);
        }

        return view('welcome', compact('blogs'));
    }
    
    public function sketch(Request $request)
    {
        $query = Blog::with('user')->where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        $blogs = $query->latest()->paginate(12)->withQueryString();

        // Return JSON partial for AJAX requests on the sketch page
        if ($request->ajax()) {
            $html = view('partials.sketch-blog-cards', compact('blogs'))->render();
            $pagination = $blogs->links()->toHtml();
            return response()->json([
                'html'       => $html,
                'pagination' => $pagination,
                'total'      => $blogs->total(),
            ]);
        }

        return view('sketch', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        $blog->increment('views');
        return view('blogs.show', compact('blog'));
    }

    public function saved()
    {
        $blogs = auth()->user()->saves()->with('blog')->latest()->paginate(12);
        return view('blogs.saved', compact('blogs'));
    }
}
