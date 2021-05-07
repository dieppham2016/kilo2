<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelChangeLog
 *
 * @property int $id
 * @property string $user_name
 * @property string $action
 * @property string|null $from
 * @property string|null $to
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereUserName($value)
 * @mixin \Eloquent
 * @property string $module
 * @method static \Illuminate\Database\Eloquent\Builder|WheelChangeLog whereModule($value)
 */
class WheelChangeLog extends Model
{
	protected $table = 'wheel_change_logs';

	protected $fillable = ['user_name', 'module', 'from', 'to', 'action', 'status'];

	protected $guarded = ['id'];

	protected $casts = [
		'from' => 'array',
		'to' => 'array'
	];
}
