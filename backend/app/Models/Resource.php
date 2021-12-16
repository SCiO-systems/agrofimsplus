<?php

namespace App\Models;

use App\Enums\PIIStatus;
use App\Enums\ResourceStatus;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'resources';

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_resource');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->save();
    }

    public function publish()
    {
        $this->status = 'published';
        $this->published_at = Carbon::now();
        $this->publisher_id = Auth::user()->id;
        $this->save();
    }

    public function setCollections($collectionIds)
    {
        $this->removeCollections();
        // TODO: Optimize.
        $collectionIds = collect($collectionIds)->unique()->all();
        foreach ($collectionIds as $id) {
            Collection::find($id)->resources()->attach($this->id);
        }
    }

    public function removeCollections()
    {
        return $this->collections()->detach();
    }

    public function setReviewTeam($userIds)
    {
        // TODO: Change this to eloquent.
        $userIds = collect($userIds)->unique()->all();
        DB::table('resource_reviewers')->where('resource_id', $this->id)->delete();
        foreach ($userIds as $id) {
            DB::table('resource_reviewers')->insert([
                'resource_id' => $this->id,
                'user_id' => $id
            ]);
        }
    }

    public function setAuthoringTeam($userIds)
    {
        // TODO: Change this to eloquent.
        $userIds = collect($userIds)->unique()->all();
        DB::table('resource_authors')->where('resource_id', $this->id)->delete();
        foreach ($userIds as $id) {
            DB::table('resource_authors')->insert([
                'resource_id' => $this->id,
                'user_id' => $id
            ]);
        }
    }

    public function files()
    {
        return $this->hasMany(ResourceFile::class);
    }

    public function thumbnails()
    {
        return $this->hasMany(ResourceThumbnail::class);
    }

    public function setOrCreateMetadataRecord($record)
    {
        $exists = DB::connection('mongodb')
            ->table('metadata_records')
            ->where('_id', $this->external_metadata_record_id)
            ->first();

        if (empty($exists)) {
            $this->external_metadata_record_id = DB::connection('mongodb')
                ->table('metadata_records')
                ->insertGetId($record);
            $this->save();
        } else {
            // Trim the record '_id' field if it exists.
            unset($record['_id']);
            DB::connection('mongodb')->table('metadata_records')
                ->where('_id', $this->external_metadata_record_id)
                ->update($record);
        }
    }

    public function getMetadataRecord()
    {
        $exists = DB::connection('mongodb')
            ->table('metadata_records')
            ->where('_id', $this->external_metadata_record_id)
            ->first();

        if (empty($exists)) {
            return null;
        }

        return $exists;
    }

    public function deleteMetadataRecord()
    {
        return DB::connection('mongodb')
            ->table('metadata_records')
            ->where('_id', $this->external_metadata_record_id)
            ->delete();
    }

    public function getAuthors()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function getReviewers()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function changeStatus($status)
    {
        $previous = $this->status;

        // We are changing the resource status to something other than under preparation.
        if ($previous === ResourceStatus::UNDER_PREPARATION && $status !== $previous) {
            $piiFailingFilesCount = $this->files()->where('pii_check_status', PIIStatus::FAILED)
                ->where('pii_terms_accepted_at')
                ->count();

            if ($piiFailingFilesCount > 0) {
                throw new Exception('Cannot send the resource for Review, The resource should pass PII check or having consent of meeting PII requirements.');
            }
        }

        $this->status = $status;
        return $this->save();
    }
}
