<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelStore
 *
 * @property int $id
 * @property int $coin
 * @property int $ticket_remaining
 * @property mixed $ticket_remaining_by_session
 * @property mixed $current_error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereCurrentError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereTicketRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereTicketRemainingBySession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelStore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WheelStore extends Model
{
	protected $table = 'wheel_stores';

	protected $fillable = ['coin', 'ticket_remaining', 'ticket_remaining_by_session', 'current_error'];

	protected $guarded = ['id'];

	protected $casts = [
		'current_error' => 'array',
		'ticket_remaining_by_session' => 'array'
	];
}
