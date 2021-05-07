<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WheelPrizePattern
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WheelWinPrize[] $prizes
 * @property-read int|null $prizes_count
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrizePattern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrizePattern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelPrizePattern query()
 * @mixin \Eloquent
 */
class WheelPrizePattern extends Model {

	protected $casts = [
		'prize_location' => 'array'
	];

	protected $guarded = ['id'];

	public $timestamps = FALSE;

	public static function message($pattern) {
		return $pattern->pattern_name;
	}


	public function prizes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
		return $this->belongsToMany(
			WheelWinPrize::class,
			'wheel_prize_pattern_wheel_win_prize',
			'wpp_id',
			'wp_id',
			'id'
		);
	}
}
