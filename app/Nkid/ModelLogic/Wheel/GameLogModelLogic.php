<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelLog;
use App\Nkid\ModelLogic\BaseModelLogic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameLogModelLogic extends BaseModelLogic
{
    const S_PENDING = 'PENDING';
    const S_COMPLETE = 'COMPLETE';

    protected $searchColumn = ['user_name', 'created_at', 'updated_at'];

    public function __construct($module = null)
    {
        $this->newQuery();
        $this->getModelReference();
    }

    public function newQuery()
    {
        $this->query = WheelLog::query();
    }

    public function getModelReference()
    {
        $model = new WheelLog();
        $this->fillable = $model->getFillable();
        unset($model);
    }

    /**
     *
     *
     * @param $ids
     * @param Request $request
     * @return JsonResponse
     */
    public function singleRecordUpdate($ids, Request $request): JsonResponse
    {
        $record = WheelLog::find($ids);
        if ($record) {
            $fill = [];
            if ($request->has('description')) {
                $fill['description'] = $request->get('description');
            }
            if ($request->has('status')) {
                if (Str::lower($request->get('status')) == 'complete') {
                    $fill['status'] = self::S_COMPLETE;
                } else {
                    $fill['status'] = self::S_PENDING;
                }
            }
            $record->update($fill);
            return \App\Nkid\ModelLogic\JsonResponse::message(201, $this->message('name', $record));
        }
        return JsonResponse::message(404, 'objectNotExist');
    }


    public function create(Request $request): JsonResponse
    {
        if ($request->has('description')) {
            return $this->log($request->get('description'));
        } else {
            return $this->log('___');
        }

    }

    private function log($description, $status = self::S_PENDING): JsonResponse
    {
        $record = new WheelLog();
        $record = $this->initFill(
            [
                'description' => $description,
                'status' => $status
            ], $record
        );
        $record->save();
        return \App\Nkid\ModelLogic\JsonResponse::message(200, '');
    }
}
