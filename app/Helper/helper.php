<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

if (!function_exists('getCurretApplicationId')) {
    function getCurretApplicationId()
    {
        return Session::get('applicationID');
    }
}

if (!function_exists('getApplicationBaseDir')) {
    function getApplicationBaseDir(): ?string
    {
        $applicationId = Session::get('applicationID');

        $applicationArray = config('nativebl.base');
        $applicationArray = array_filter($applicationArray, function ($value) {
            if (is_string($value)) {
                return str_contains($value, '_application_id');
            }
        });

        $applicationFlippedArray = array_flip($applicationArray);

        $applicationSlug = $applicationFlippedArray[$applicationId] ?? null;

        if (!$applicationId || $applicationSlug == null) {
            $applicationDir = 'common';
        } else {
            $applicationDir = explode('_application_id', $applicationSlug)[0];
        }

        $currentDate = Carbon::now()->format('d-m-Y');

        return 'assets/' . $applicationDir . '/' . $currentDate;
    }
}

if (!function_exists('getDateArray')) {
    function getDateArray(Request $request): array
    {
        $individualDate = $request->input('filters.individual_date');

        $newDate = explode(" - ", $individualDate);
        $data['startDate'] = $newDate[0] ?? $request->startDate;
        $data['endDate'] = $newDate[1] ?? $request->endDate;

        return $data;
    }
}

if (!function_exists('getCurrentDisk')) {
    function getCurrentDisk(): Filesystem
    {
        return Storage::disk(env('FILESYSTEM_DISK'));
    }
}

if (!function_exists('fileUpload')) {
    /**
     * @throws Exception
     */
    function fileUpload(UploadedFile $file, $uploadPath, array $rules = []): ?string
    {
        // Default rules if none provided
        $defaultRules = [
            'file' => 'required|mimes:' . implode(',', config('nativebl.base.tiger_web_upload_file_type')) . '|max:' . config('nativebl.base.tiger_web_upload_file_size'),
        ];

        $rules = empty($rules) ? $defaultRules : $rules;

        $validator = Validator::make(['file' => $file], $rules);

        if ($validator->fails()) {
            // Handle the validation errors.
            // You can throw an exception or return some kind of error response here.
            throw ValidationException::withMessages(['file' => $validator->errors()->first()]);
        }

        // Ensure the upload path exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        return $file->storePublicly($uploadPath);

    }
}


if (!function_exists('getChildrenKeys')) {
    function getChildrenKeys(array $tree, array &$keys = []): array
    {
        if (!isset($tree['children']) || !is_array($tree['children'])) {
            return $keys;
        }

        foreach ($tree['children'] as $key => $child) {
            $keys[] = $key;
            getChildrenKeys($child, $keys);
        }

        return $keys;
    }
}

if (!function_exists('getESimFilter')) {
    function getESimFilter(Request $request): int|null
    {
        return $request->input('filters.msisdn');
    }
}
