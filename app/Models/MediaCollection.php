<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * @property mixed original_name
 * @property mixed display_name
 * @property mixed type
 * @method static findOrFail($id)
 * @method static where(string $string, $collectionOriginalName)
 * @method static whereOriginalName($collectionOriginalName)
 * @method static whereType(string $string)
 * @method static whereIn()
 */
class MediaCollection extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'media_collections';

    protected $fillable = [
        'original_name',
        'display_name',
        'type',
    ];

}
