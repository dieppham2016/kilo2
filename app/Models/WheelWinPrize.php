<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * App\Models\WheelWinPrize
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WheelPrizePattern[] $prizePatterns
 * @property-read int|null $prize_patterns_count
 * @method static \Illuminate\Database\Eloquent\Builder|WheelWinPrize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelWinPrize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelWinPrize query()
 * @mixin \Eloquent
 */
class WheelWinPrize extends Model {
	//
	protected $table = 'wheel_win_prizes';

	protected $guarded = ['id'];

	public $timestamps = FALSE;

	public static function search($keyword, $query) {
		if ($keyword) {
			return $query->where('name', 'like', '%' . $keyword . '%')
				->orWhere('more_info', 'like', '%' . $keyword . '%');
		}
		return $query;
	}

	public static function prizeNameByType(Request $request) {
		if ($request->type == 'Ticket' || $request->type == 'Bonus') {
			return $request->type;
		}
		return $request->name;
	}

	public static function descriptionForTicket(Request $request) {
		if ($request->type == 'Ticket') {
			return $request->amount . " tiNi điểm.";
		}
		if ($request->type == 'Bonus') {
			return "Bonus with rate: " . $request->rate . "%";
		}
		return $request->more_info;
	}

	public static function message($prize) {
		return in_array($prize->type, ['Ticket', 'Bonus']) ? $prize->more_info : $prize->name;
	}

	public function prizePatterns(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
		return $this->belongsToMany(
			WheelPrizePattern::class,
			'wheel_prize_pattern_wheel_win_prize',
			'wp_id',
			'wpp_id',
			'id'
		);
	}
}
