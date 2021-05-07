<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static updateOrInsert(array $array, array $array1)
 * @method static first()
 * @method static get()
 */
class QueueContent extends Model
{
    protected $table = 'queue_contents';

    protected $fillable = [
        'header_vi',
        'header_en',
        'collection_ids',
        'mode_active',
    ];

    public function collection() {
        return $this->belongsToMany(MediaCollection::class);
    }
}
