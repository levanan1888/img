<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ImageConverter
{
    private const MAX_BMP_OUTPUT_BYTES = 50 * 1024 * 1024;

    /** @return array{path: string, name: string, size: int} */
    public function convert(UploadedFile $file, string $targetFormat = 'png'): array
    {
        $workingDirectory = $this->ensureWorkingDirectory();
        $sourcePath = $this->buildSourcePath($file, $workingDirectory);
        $outputPath = $workingDirectory . DIRECTORY_SEPARATOR . pathinfo($sourcePath, PATHINFO_FILENAME) . '.' . $targetFormat;
        $outputFileName = $this->buildOutputFileName($file, $targetFormat);
        $image = null;

        $file->move($workingDirectory, basename($sourcePath));

        try {
            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

            $image = match ($extension) {
                'jpeg', 'jpg' => imagecreatefromjpeg($sourcePath),
                'webp' => imagecreatefromwebp($sourcePath),
                'bmp' => imagecreatefrombmp($sourcePath),
                'png' => imagecreatefrompng($sourcePath),
                default => throw new RuntimeException('Unsupported image type: ' . $extension),
            };

            if (! $image) {
                throw new RuntimeException('Failed to load image file.');
            }

            if ($targetFormat === 'png' || $targetFormat === 'webp') {
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }

            $saved = match ($targetFormat) {
                'png' => imagepng($image, $outputPath),
                'jpg', 'jpeg' => imagejpeg($image, $outputPath, 90),
                'webp' => imagewebp($image, $outputPath, 85),
                'bmp' => $this->saveBmp($image, $outputPath),
                default => throw new RuntimeException('Unsupported output format: ' . $targetFormat),
            };

            imagedestroy($image);
            $image = null;

            if (! $saved) {
                throw new RuntimeException('Failed to write output file.');
            }
        } catch (Throwable $exception) {
            if ($image instanceof \GdImage) {
                imagedestroy($image);
            }

            $this->deleteFile($sourcePath);
            $this->deleteFile($outputPath);

            report($exception);

            throw new RuntimeException(
                'Image conversion failed: ' . $exception->getMessage(),
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

    private function saveBmp(\GdImage $image, string $outputPath): bool
    {
        $this->assertBmpOutputSizeIsAllowed($image);

        return imagebmp($image, $outputPath);
    }

    private function assertBmpOutputSizeIsAllowed(\GdImage $image): void
    {
        $estimatedBytes = (imagesx($image) * imagesy($image) * 3) + 54;

        if ($estimatedBytes <= self::MAX_BMP_OUTPUT_BYTES) {
            return;
        }

        throw new RuntimeException('BMP output is too large. Please choose PNG, JPG, WEBP, or upload a smaller image.');
    }

    private function buildSourcePath(UploadedFile $file, string $workingDirectory): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $safeName = Str::uuid() . '.' . $extension;

        return $workingDirectory . DIRECTORY_SEPARATOR . $safeName;
    }

    private function buildOutputFileName(UploadedFile $file, string $targetFormat): string
    {
        return Str::of($file->getClientOriginalName())
            ->beforeLast('.')
            ->append('.' . $targetFormat)
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