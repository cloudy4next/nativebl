<?php

declare(strict_types=1);


namespace App\Repositories\DND;

use App\Contracts\DND\BulkUploadDNDRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\DND\BulkMasterDND;
use NativeBL\Repository\AbstractNativeRepository;
use App\Models\DND\DNDChannel;
use App\Models\DND\MasterDND;
use Illuminate\Support\Collection;
use App\Traits\APITrait;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\DND\BulkMasterDetail;
use Illuminate\Support\Facades\Bus;

class BulkUploadDNDRepository extends AbstractNativeRepository implements BulkUploadDNDRepositoryInterface
{
    use APITrait;

    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {

        $data = BulkMasterDND::select('dnd_bulk_master.*')
            ->selectRaw('SUM(dnd_bulk_details.is_processed = 1) as completed')
            ->selectRaw('SUM(dnd_bulk_details.is_processed = 0) as pending')
            ->selectRaw('CASE
                            WHEN dnd_bulk_master.process_status = 3 THEN "completed"
                            WHEN dnd_bulk_master.process_status = 2 THEN "processing"
                            WHEN dnd_bulk_master.process_status = 1 THEN "pending"
                        END as process_status_label')
            ->leftJoin('dnd_bulk_details', 'dnd_bulk_master.id', '=', 'dnd_bulk_details.bulk_master_id')
            ->groupBy('dnd_bulk_master.id', 'process_status_label')
            ->get();


        return $data;
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $data = $data->filter(function ($item) use ($field, $value) {
                    return $item[$field] !== null && stripos($item[$field], $value) !== false;
                });
            }
        }
        return $data;
    }



    public function channels(): array
    {
        $channel = DNDChannel::pluck('channel_name')->toArray();
        return $channel = array_values(array_diff($channel, ['INVALID']));
    }

    public function getExportData()
    {
        return MasterDND::select('dnd_master.*', 'dnd_channels.channel_name')
            ->join('dnd_channels', 'dnd_master.dnd_channel_id', '=', 'dnd_channels.id')
            ->get();
    }

    public function saveUplaodedData($file, $shecduleDate)
    {

        if ($file->isValid()) {

            $data = Excel::toArray([], $file);

            if (empty($data) || empty($data[0])) {
                throw new NotFoundException('No data found in the uploaded file.');
            }
            $fileName = $this->fileUploadDND($file);

            $rowCount = count($data[0]) - 1;

            try {
                DB::beginTransaction();

                $currentTime = Carbon::now()->toDateTimeString();
                $userId = Auth::id();

                $bulkMasterData = [
                    'filename' => $fileName,
                    'process_status' => 1,
                    'creation_date' => $currentTime,
                    'schedule_process_time' => $shecduleDate,
                    'user_id' => $userId,
                    'record_count' => $rowCount,
                ];

                $bulkMaster = BulkMasterDND::create($bulkMasterData);

                $firstRowSkipped = false;

                foreach ($data[0] as $row) {
                    if (!$firstRowSkipped) {
                        $firstRowSkipped = true;
                        continue;
                    }

                    $bulkMasterDetailData = [
                        'bulk_master_id' => $bulkMaster->id,
                        'creation_date' => $currentTime,
                        'channel_name' => $row[0],
                        'msisdn' => $row[1],
                        'request_type' => $row[2],
                        'is_processed' => 0,
                    ];

                    BulkMasterDetail::create($bulkMasterDetailData);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new NotFoundException($e->getMessage());
            }
        }
    }

    public function fileUploadDND($file): string
    {
        $currentDate = Carbon::now()->format('d-m-Y-h-i-s-A');
        $destinationPath = 'assets/dnd_uploads/' . $currentDate;

        if (!Storage::exists($destinationPath)) {
            Storage::makeDirectory($destinationPath, 0755, true);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $customFilename = $originalFilename . '_' . $currentDate . '.' . $extension;

        $file->storeAs($destinationPath, $customFilename);
        return $customFilename;
    }
}
