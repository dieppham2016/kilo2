<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @property mixed name
 * @property mixed description
 * @property mixed display_level
 * @property string created_at
 * @property string updated_at
 */
class Status extends Model
{
    protected $table = 'statuses';

    protected $fillable = [
        'name',
        'description',
        'display_level'
    ];

    public $timestamps = true;


    public function bill() {
        return $this->hasMany(Bill::class);
    }
}
