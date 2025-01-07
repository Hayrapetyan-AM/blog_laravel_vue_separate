<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
class BlogService
{
    private const IMAGE_DIR = 'blogImages';
    private const VALIDATION_RULES = [
        'title' => ['required', 'string', 'max:255'],  // Title seems fine with max length of 255
        'context' => ['required', 'string'],  // If context can be long text, remove the 'max:255' restriction
    ];

    private array $validatedData = [];

    public function __construct(
        private readonly Request $request
    )
    {
    }

    public function validate(): self
    {
        // Define the validation rules, and add the image validation rules if an image is uploaded
        $validationRules = self::VALIDATION_RULES;
        // If the request has a file for image, add the file validation rules
        if ($this->request->hasFile('image')) {
            $validationRules['image'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'];
        } else {
            // If no image file is provided, allow null or an existing string value (path or URL)
            $validationRules['image'] = ['nullable', 'string'];
        }

        // Validate the data with the merged validation rules
        $this->validatedData = $this->request->validate($validationRules);

        // Handle the file upload if there's an image
        if ($this->request->hasFile('image')) {
            $fileUploadService = new FileUploadService($this->request->file('image'), self::IMAGE_DIR);
            $this->validatedData['image'] = $fileUploadService->upload()->getFileName();
        }

        return $this;
    }

    public function all(): Collection
    {
        return Blog::all();
    }

    /**
     * @throws \Exception
     */
    public function store(): Blog
    {
        // Ensure validation has been run before storing data
        if (empty($this->validatedData)) {
            throw new \Exception('Data has not been validated. Call validate() before store().');
        }
        // Create and save the blog post
        return Blog::create([
            'user_id' => $this->request->user()->id,
            'title' => $this->validatedData['title'],
            'context' => $this->validatedData['context'],
            'image' => $this->validatedData['image'] ?? null, // Save image if it exists
        ]);
    }


    public function update(): void
    {
        // Ensure validation has been run before storing data
        if (empty($this->validatedData)) {
            throw new \Exception('Data has not been validated. Call validate() before store().');
        }

        // Find the existing blog post and update it
        $blog = $this->findOne();  // This will find the blog by ID or throw an exception

        $blog->update([
            'title' => $this->validatedData['title'],
            'context' => $this->validatedData['context'],
            'image' => $this->validatedData['image'] ?? $blog->image, // Update image only if provided
        ]);
    }

    protected function findOne(): Blog
    {
        // This ensures you are finding the existing blog and not creating a new one
        return Blog::findOrFail($this->request->get('id'));
    }


    public function destroy(): void
    {

    }

}
