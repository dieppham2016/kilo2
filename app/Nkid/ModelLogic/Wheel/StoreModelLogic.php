<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelStore;
use App\Nkid\ModelLogic\BaseModelLogic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreModelLogic extends BaseModelLogic {

	protected $guard = ['coin'];

	public function newQuery() {
		$this->query = WheelStore::query();
	}

	public function getModelReference() {
		$model = new WheelStore();
		$this->fillable = $model->getFillable();
		unset($model);
	}

	public function singleRecordUpdate($ids, Request $request): JsonResponse {
		$record = WheelStore::first();
		if ($record) {
			$this->updateRecord($record, $request);
			return \App\Nkid\ModelLogic\JsonResponse::message(201, '');
		} else {
			$record = new WheelStore();
			$record = $this->initFillFromRequest($request, $record);
			$record->save();
			$this->updateRecord($record, $request);
			return \App\Nkid\ModelLogic\JsonResponse::message(201, '');
		}
	}


	private function updateRecord(Model $model, Request $request) {
		if ($request->has('coin') &&
			is_numeric($request->get('coin')) &&
			(int)$request->get('coin') > 0) {
			$model->increment('coin', abs((int)$request->get('coin')));
		} elseif ($request->has('coin') &&
			is_numeric($request->get('coin')) &&
			(int)$request->get('coin') < 0 && $model->coin > 0) {
			$model->decrement('coin', abs((int)$request->get('coin')));
		}
		$model->update($this->initFillFromRequest($request));

	}

}
