<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Blog/Index', [
            'blogs' => Blog::with('user')->where('user_id', auth()->id())->latest()->paginate(10),
            'user' => auth()->user()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Blog/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = new BlogService($request);
        $service->validate()->store();

        return redirect()->route('blogs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(blog $blog)
    {
        return Inertia::render('Blog/Show', [
            'blog' => $blog,
            'user' => $blog->user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(blog $blog): Response
    {
        return Inertia::render('Blog/Edit', [
            'blog' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $service = new BlogService($request);
        $service->validate()->update();

        return redirect()->route('blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog): \Illuminate\Http\RedirectResponse
    {
        Blog::destroy($blog->id);

        return redirect()->route('blogs.index');
    }
}
