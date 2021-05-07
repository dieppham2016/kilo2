<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelConfig
 *
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $io_ticket_pwm
 * @property int $io_ticket_input
 * @property int $io_ticket_output
 * @property int $time_back_demo
 * @property int $time_auto_play
 * @property int $time_show_congratulation_short
 * @property int $time_show_congratulation_long
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereIoTicketInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereIoTicketOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereIoTicketPwm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereTimeAutoPlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereTimeBackDemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereTimeShowCongratulationLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelConfig whereTimeShowCongratulationShort($value)
 */
class WheelConfig extends Model {
	protected $table = 'wheel_configs';

	protected $fillable = [
		'io_ticket_pwm',
		'io_ticket_input',
		'io_ticket_output',
		'time_back_demo',
		'time_auto_play',
		'time_show_congratulation_short',
		'time_show_congratulation_long',
	];

	public $timestamps = FALSE;
}
