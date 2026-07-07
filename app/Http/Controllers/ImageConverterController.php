<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertImageRequest;
use App\Services\ImageConverter;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ImageConverterController extends Controller
{
    public function __invoke(ConvertImageRequest $request, ImageConverter $converter): BinaryFileResponse|JsonResponse
    {
        $startTime = microtime(true);
        $file = $request->file('document');
        $targetFormat = $request->input('target_format', 'png');

        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $sourceFormat = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $ipAddress = $request->ip();

        try {
            $imageResult = $converter->convert(
                $file,
                $targetFormat
            );

            // Log success
            \App\Models\ConversionLog::create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'source_format' => $sourceFormat,
                'target_format' => $targetFormat,
                'status' => 'success',
                'execution_time' => microtime(true) - $startTime,
                'ip_address' => $ipAddress,
            ]);

        } catch (Throwable $exception) {
            report($exception);

            // Log failure
            \App\Models\ConversionLog::create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'source_format' => $sourceFormat,
                'target_format' => $targetFormat,
                'status' => 'failed',
                'execution_time' => microtime(true) - $startTime,
                'error_message' => $exception->getMessage(),
                'ip_address' => $ipAddress,
            ]);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        // Determine correct Image MIME Type
        $mime = 'image/png';
        $ext = strtolower(pathinfo($imageResult['path'], PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $mime = 'image/jpeg';
                break;
            case 'webp':
                $mime = 'image/webp';
                break;
            case 'bmp':
                $mime = 'image/bmp';
                break;
        }

        return response()
            ->download($imageResult['path'], $imageResult['name'], [
                'Content-Type' => $mime,
                'X-Generated-Image-Size' => (string) $imageResult['size'],
            ])
            ->deleteFileAfterSend(true);
    }
}
