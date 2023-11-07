<?php

namespace App\Traits;

use Session;
use Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Symfony\Component\String\ByteString;

/**
 * 🖼️ ImageUploadTrait contains image upload functionality.
 * @author cloudy4next <jahangir7200@live.com>
 */
trait AttachmentUploadTrait
{
    public function upload($file): string
    {
        return $this->apiResponse('POST', null, $this->processAttachment($file), '/Api/Conf/SaveFile')->data;
    }
    public function processAttachment($file): array
    {
        $attachmentData = file_get_contents($file);
        $base64String = base64_encode($attachmentData);
        $extenstion = $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();

        return [
            'fileName' => $name,
            'fileType' => $extenstion,
            'fileContent' => $base64String,
        ];
    }

    public function getBase64Attachment(?string $fileId): ?string
    {
        if ($fileId == null) {
            return null;
        }
        $path = '/Api/OpenAPI/GetFile?fileID=' . $fileId;
        $data = $this->apiResponse('GET', null, null, $path)->data;

        return $data;
    }

}
