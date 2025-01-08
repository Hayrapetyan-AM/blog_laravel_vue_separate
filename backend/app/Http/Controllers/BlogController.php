<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $blogs = Blog::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json([
            'blogs' => $blogs,
            'user' => auth()->user(),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Not applicable for API-based JSON responses.
     */
    public function create(): JsonResponse
    {
        return response()->json([
            'message' => 'Endpoint not applicable for API responses.',
        ], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $service = new BlogService($request);
        $service->validate()->store();

        return response()->json([
            'message' => 'Blog created successfully.',
            'success' => true
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog): JsonResponse
    {
        return response()->json([
            'blog' => $blog,
            'user' => $blog->user,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Not applicable for API-based JSON responses.
     */
    public function edit(Blog $blog): JsonResponse
    {
        return response()->json([
            'message' => 'Endpoint not applicable for API responses.',
        ], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog): JsonResponse
    {
        $service = new BlogService($request);
        $service->validate()->update();

        return response()->json([
            'message' => 'Blog updated successfully.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog, Request $request): JsonResponse
    {
        if($blog->user_id !== $request->user()->id) {
            $service = new BlogService($request);
            $service->respondWithOwnershipError();
        }

        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully.',
        ], 200);
    }
}
