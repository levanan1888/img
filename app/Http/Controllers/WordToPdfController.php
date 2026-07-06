<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertWordToPdfRequest;
use App\Services\WordToPdfConverter;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class WordToPdfController extends Controller
{
    public function __invoke(ConvertWordToPdfRequest $request, WordToPdfConverter $converter): BinaryFileResponse|JsonResponse
    {
        try {
            $imageResult = $converter->convert(
                $request->file('document'),
                $request->input('target_format', 'png')
            );
        } catch (Throwable $exception) {
            report($exception);

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
