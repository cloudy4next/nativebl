<?php

namespace App\Http\Controllers\Common;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\FileUploadRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FileController extends Controller
{

    public function fileUpload(FileUploadRequest $fileUpload): JsonResponse
    {
        try {

            $destinationPath = getApplicationBaseDir();

            if (!$destinationPath) {
                throw new Exception("Failed to get the application ID.");
            }

            $file = $fileUpload->file('upload');

            $fileUrl = fileUpload($file, $destinationPath);

            if (!$fileUrl) {
                return response()->json([
                    'error' => 'Failed to upload the file.'
                ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['fileName' => $fileUpload->file('upload')->getClientOriginalName(), 'uploaded' => 1, 'url' => asset($fileUrl)], ResponseAlias::HTTP_OK);

        } catch (NotFoundException $e) {
            return response()->json([
                'error' => 'NotFoundException occurred: ' . $e->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {

        }
    }

}
