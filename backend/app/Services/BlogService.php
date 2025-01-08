<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogService
{
    private const IMAGE_DIR = 'blogImages';
    private const VALIDATION_RULES = [
        'title' => ['required', 'string', 'max:255'], // Title validation
        'context' => ['required', 'string'],          // Context validation
    ];

    private array $validatedData = [];

    public function __construct(
        private readonly Request $request
    )
    {
    }

    /**
     * Validate the request data.
     *
     * @return $this
     */
    public function validate(): self
    {
        // Merge custom rules with dynamic rules for image validation
        $validationRules = self::VALIDATION_RULES;

        if ($this->request->hasFile('image')) {
            $validationRules['image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'];
        } else {
            $validationRules['image'] = ['nullable', 'string'];
        }

        // Validate the data
        $validator = Validator::make($this->request->all(), $validationRules);

        if ($validator->fails()) {
            $this->respondWithValidationError($validator);
        }

        $this->validatedData = $validator->validated();

        // Handle file upload if there's an image
        if ($this->request->hasFile('image')) {
            $fileUploadService = new FileUploadService($this->request->file('image'), self::IMAGE_DIR);
            $this->validatedData['image'] = $fileUploadService->upload()->getFileName();
        }
        return $this; // Enable chaining
    }

    /**
     * Store a new blog post.
     *
     * @return Blog
     */
    public function store(): Blog
    {
        if (empty($this->validatedData)) {
            throw new \Exception('Data has not been validated. Call validate() before store().');
        }

        return Blog::create([
            'user_id' => $this->request->user()->id,
            'title' => $this->validatedData['title'],
            'context' => $this->validatedData['context'],
            'image' => $this->validatedData['image'] ?? null,
        ]);
    }

    /**
     * Update an existing blog post.
     *
     * @return void
     */
    public function update(): void
    {
        if (empty($this->validatedData)) {
            throw new \Exception('Data has not been validated. Call validate() before update().');
        }

        $blog = $this->findOne();

        if($blog->user_id !== $this->request->user()->id) {
            $this->respondWithOwnershipError();
        }

        $blog->update([
            'title' => $this->validatedData['title'],
            'context' => $this->validatedData['context'],
            'image' => $this->validatedData['image'] ?? $blog->image,
        ]);
    }

    /**
     * Find a blog by ID.
     *
     * @return Blog
     */
    protected function findOne(): Blog
    {
        return Blog::findOrFail($this->request->get('id'));
    }

    public function respondWithOwnershipError(): JsonResponse
    {
        response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => [
                'title' => ['Permission denied.'],
            ],
        ], 422)->send();
        exit; // Stop further execution
    }

    /**
     * Handle validation error response.
     *
     * @param $validator
     * @return JsonResponse
     */
    private function respondWithValidationError($validator): JsonResponse
    {
        response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422)->send();
        exit; // Stop further execution
    }

    public function all(): Collection
    {
        return Blog::all();
    }

    public function destroy(): void
    {
        $blog = $this->findOne();
        $blog->delete();
    }
}
