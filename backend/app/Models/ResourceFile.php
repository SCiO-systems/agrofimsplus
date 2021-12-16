<?php

namespace App\Models;

use App\Enums\PIIStatus;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'resource_files';

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setPIICheckIdentifier($id)
    {
        $this->pii_check_status_identifier = $id;
        $this->save();
    }

    public function setPIIStatus($status)
    {
        if (!in_array($status, PIIStatus::getValues())) {
            throw new Exception('An invalid status was provided, the list of valid statuses includes: ' . implode(',', PIIStatus::getValues()));
        }

        $this->pii_check_status = $status;

        $currentStatus = 'pending';
        if ($status === PIIStatus::FAILED) {
            $currentStatus = 'fail';
        } else if ($status === PIIStatus::PASSED) {
            $currentStatus = 'pass';
        }

        $metadataRecord = DB::connection('mongodb')
            ->table('metadata_records')
            ->where('_id', $this->resource->external_metadata_record_id)
            ->first();

        $id = $this->id;

        if (isset($metadataRecord['resource_files'])) {
            $resourceFiles = collect($metadataRecord['resource_files'])->map(
                function ($item) use ($id, $currentStatus) {
                    if ($id === $item["id"]) {
                        $item['pii_check'] = $currentStatus;
                    }
                    return $item;
                }
            )->toArray();

            DB::connection('mongodb')
                ->table('metadata_records')
                ->where('_id', $metadataRecord['_id'])
                ->update(['resource_files' => $resourceFiles]);
        }

        return $this->save();
    }

    public function acceptTerms()
    {
        if (empty($this->pii_terms_accepted_at)) {
            $this->pii_terms_accepted_at = now();
            $this->save();
        }
    }
}
