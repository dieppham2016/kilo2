<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelPattern
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property mixed $prizes_location
 * @property string $type
 * @property int|null $prize_id
 * @property int $bonus_min
 * @property int $bonus_max
 * @property int $bonus_current
 * @property int|null $bonus_fixed
 * @property int $bonus_rate
 * @property int $bonus_auto_increment
 * @property int $bonus_enable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wheel\WheelPrize[] $prizes
 * @property-read int|null $prizes_count
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusAutoIncrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusFixed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereBonusRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern wherePrizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern wherePrizesLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPattern whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WheelPattern extends Model
{
	protected $table = 'wheel_patterns';

	protected $fillable = [
		'name',
		'alias',
		'prizes_location',
		'type',
		'prize_id',
		'bonus_min',
		'bonus_max',
		'bonus_current',
		'bonus_fixed',
		'bonus_rate',
		'bonus_auto_increment',
		'bonus_enable',
	];

	protected $guarded = ['id'];

	protected $casts = [
		'prizes_location' => 'array'
	];

	public function prizes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
		return $this->belongsToMany(
			WheelPrize::class,
			'wheel_pattern_wheel_prize',
			'pattern_id',
			'prize_id',
			'id'
		);
	}


}
