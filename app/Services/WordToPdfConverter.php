<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class WordToPdfConverter
{
    /** @return array{path: string, name: string, size: int} */
    public function convert(UploadedFile $file, string $targetFormat = 'png'): array
    {
        $workingDirectory = $this->ensureWorkingDirectory();
        $sourcePath = $this->buildSourcePath($file, $workingDirectory);
        $outputPath = $workingDirectory.DIRECTORY_SEPARATOR.pathinfo($sourcePath, PATHINFO_FILENAME).'.'.$targetFormat;
        $outputFileName = $this->buildOutputFileName($file, $targetFormat);

        $file->move($workingDirectory, basename($sourcePath));

        try {
            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

            // Load image using native GD functions based on original extension
            switch ($extension) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($sourcePath);
                    break;
                case 'bmp':
                    $image = imagecreatefrombmp($sourcePath);
                    break;
                case 'png':
                    $image = imagecreatefrompng($sourcePath);
                    break;
                default:
                    throw new RuntimeException('Unsupported image type: ' . $extension);
            }

            if (!$image) {
                throw new RuntimeException('Failed to load image file.');
            }

            // Enable transparency mapping for transparent target formats
            if ($targetFormat === 'png' || $targetFormat === 'webp') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }

            // Export as requested format
            switch ($targetFormat) {
                case 'png':
                    $saved = imagepng($image, $outputPath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $saved = imagejpeg($image, $outputPath, 90);
                    break;
                case 'webp':
                    $saved = imagewebp($image, $outputPath, 85);
                    break;
                case 'bmp':
                    $saved = imagebmp($image, $outputPath);
                    break;
                default:
                    throw new RuntimeException('Unsupported output format: ' . $targetFormat);
            }

            imagedestroy($image);

            if (!$saved) {
                throw new RuntimeException('Failed to write output file.');
            }

        } catch (Throwable $exception) {
            $this->deleteFile($sourcePath);
            $this->deleteFile($outputPath);

            report($exception);

            throw new RuntimeException(
                'Image conversion failed: '.$exception->getMessage(),
                previous: $exception,
            );
        }

        $this->deleteFile($sourcePath);

        if (! is_file($outputPath) || filesize($outputPath) === 0) {
            $this->deleteFile($outputPath);

            throw new RuntimeException('The converter did not generate a valid image file.');
        }

        return [
            'path' => $outputPath,
            'name' => $outputFileName,
            'size' => filesize($outputPath),
        ];
    }

    private function buildSourcePath(UploadedFile $file, string $workingDirectory): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $safeName = Str::uuid().'.'.$extension;

        return $workingDirectory.DIRECTORY_SEPARATOR.$safeName;
    }

    private function buildOutputFileName(UploadedFile $file, string $targetFormat): string
    {
        return Str::of($file->getClientOriginalName())
            ->beforeLast('.')
            ->append('.'.$targetFormat)
            ->replaceMatches('/[^A-Za-z0-9._-]+/', '_')
            ->toString();
    }

    private function ensureWorkingDirectory(): string
    {
        $directory = storage_path('app/conversions');

        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException('Unable to create the temporary conversion directory.');
        }

        return $directory;
    }

    private function deleteFile(string $path): void
    {
        if (is_file($path)) {
            @unlink($path);
        }
    }
}