<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIn(string $string, array $ids_to_delete)
 * @method static findOrFail(int $id)
 * @property mixed bill_no
 * @property mixed status_id
 */
class Bill extends Model
{
    protected $table = 'bills';

    protected $fillable = [
        'bill_no',
        'status_id',
    ];

    public $timestamps = true;

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public static function whereBetweenDate($query, $date) {
        if (!empty($date)) {
            if (is_array($date)) {
                $startDate = date('Y-m-d 00:00:00', strtotime($date[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($date[1]));
                if ($startDate !== $endDate) $query->whereBetween('created_at', [$startDate, $endDate]);
                else $query->whereDate('created_at', $endDate);
            } else $query->whereDate('created_at', date('Y-m-d', strtotime($date)));
        }
        return $query;
    }

    public static function whereStatus($query, $status) {
        if (!empty($status)) {
            if (is_array($status)) $query->whereIn('status_id', $status);
            else $query->where('status_id', $status);
        }
        return $query;
    }
}
