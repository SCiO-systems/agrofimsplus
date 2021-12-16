<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionResource extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'collection_resource';

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }
}
