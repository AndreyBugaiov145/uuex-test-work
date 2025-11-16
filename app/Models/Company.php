<?php

namespace App\Models;

use App\Contracts\VersionableInterface;
use App\Traits\Versionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model implements VersionableInterface
{
    use HasFactory;
    use Versionable;

    protected $fillable = [
        'name',
        'edrpou',
        'address',
    ];
}
