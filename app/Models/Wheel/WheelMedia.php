<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelMedia
 *
 * @property int $id
 * @property string|null $demo_screen
 * @property string|null $demo_music
 * @property string|null $play_screen
 * @property string|null $play_music
 * @property string|null $congratulation_screen
 * @property string|null $congratulation_music
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereCongratulationMusic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereCongratulationScreen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereDemoMusic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereDemoScreen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia wherePlayMusic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia wherePlayScreen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WheelMedia extends Model
{
    //
}
