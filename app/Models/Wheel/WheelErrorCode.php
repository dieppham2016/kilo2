<?php

namespace App\Models\Wheel;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wheel\WheelErrorCode
 *
 * @property int $id
 * @property string $code
 * @property string $code_name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereCodeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $code_group
 * @property int $code_id
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereCodeGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WheelErrorCode whereCodeId($value)
 */
class WheelErrorCode extends Model
{
    //
}
