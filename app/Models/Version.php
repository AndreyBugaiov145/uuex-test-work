<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'versionable_id',
        'versionable_type',
        'version',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function versionable()
    {
        return $this->morphTo();
    }
}
