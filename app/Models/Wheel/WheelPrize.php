<?php

namespace App\Models\Wheel;

use App\Nkid\ModelLogic\Wheel\PrizeModelLogic;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelPrize
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $rate
 * @property int $amount
 * @property string|null $more_info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wheel\WheelPattern[] $prizePatterns
 * @property-read int|null $prize_patterns_count
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereMoreInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrize whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WheelPrize extends Model {
	protected $table = 'wheel_prizes';

	protected $fillable = ['name', 'type', 'amount', 'more_info', 'rate'];

	protected $guarded = ['id'];


	public function prizePatterns(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
		return $this->belongsToMany(
			WheelPattern::class,
			'wheel_pattern_wheel_prize',
			'prize_id',
			'pattern_id',
			'id'
		)->select(['name', 'alias']);
	}

}
