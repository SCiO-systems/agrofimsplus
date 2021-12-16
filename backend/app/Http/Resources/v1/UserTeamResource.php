<?php

namespace App\Http\Resources\v1;

use App\Enums\ResourceStatus;
use App\Models\Collection;
use App\Models\Resource;
use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resources = Resource::where('team_id', $this->id)
            ->whereIn('status', [
                ResourceStatus::UNDER_PREPARATION,
                ResourceStatus::UNDER_REVIEW,
                ResourceStatus::APPROVED
            ])->get();

        $activeTasks = collect($resources)->reduce(function ($sum, $task) {
            if ($task->status === ResourceStatus::UNDER_PREPARATION) {
                $sum++;
            }
            return $sum;
        }, 0);

        $pendingReviewTasks = collect($resources)->reduce(function ($sum, $task) {
            if ($task->status === ResourceStatus::UNDER_REVIEW) {
                $sum++;
            }
            return $sum;
        }, 0);

        $pendingUploadTasks = collect($resources)->reduce(function ($sum, $task) {
            if ($task->status === ResourceStatus::APPROVED) {
                $sum++;
            }
            return $sum;
        }, 0);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'active_tasks' => $activeTasks,
            'pending_review_tasks' => $pendingReviewTasks,
            'pending_upload_tasks' => $pendingUploadTasks,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
