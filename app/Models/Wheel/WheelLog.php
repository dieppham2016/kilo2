<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelLog
 *
 * @property int $id
 * @property string $description
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WheelLog extends Model
{
    protected $table = 'wheel_logs';

    protected $fillable = ['description', 'status'];

    protected $guarded = ['id'];

}
