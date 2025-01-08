<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    protected string $filename;

    public function __construct(
        protected UploadedFile $file,
        protected string $dirname
    ) {
    }

    /**
     * Upload the file to the specified directory in the public folder.
     *
     * @return $this
     */
    public function upload(): self
    {
        // Sanitize file name and add a timestamp
        $this->filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $this->file->getClientOriginalName());

        // Create the directory if it doesn't exist
        $directory = public_path($this->dirname);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Move the file to the public directory
        $this->file->move($directory, $this->filename);

        return $this;
    }

    /**
     * Get the relative path to the uploaded file.
     *
     * @return string
     */
    public function getFileName(): string
    {
        return "{$this->dirname}/{$this->filename}";
    }

    /**
     * Get an image from the public directory by its path.
     *
     * @param string $imagePath
     * @return \Symfony\Component\HttpFoundation\File\File|null
     */
    public function getImageByPath(string $imagePath)
    {
        $fullPath = public_path($imagePath);

        // Check if the image exists
        if (File::exists($fullPath)) {
            return new \Symfony\Component\HttpFoundation\File\File($fullPath);
        }

        return null; // Return null if the file doesn't exist
    }
}
