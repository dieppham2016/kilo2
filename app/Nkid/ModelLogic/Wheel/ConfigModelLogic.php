<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelConfig;
use App\Nkid\ModelLogic\BaseModelLogic;
use App\Nkid\ModelLogic\JsonResponse;
use Illuminate\Http\Request;

class ConfigModelLogic extends BaseModelLogic {

	private $changeLogModel;

	public const MODULE = 'CONFIG';

	public function newQuery() {
		$this->query = WheelConfig::query();
	}

	public function getModelReference() {
		$model = new WheelConfig();
		$this->fillable = $model->getFillable();
		unset($model);
		$this->changeLogModel = new ChangeLogModelLogic(self::MODULE);
	}

	public function singleRecordUpdate($ids, Request $request): \Illuminate\Http\JsonResponse {
		$record = WheelConfig::first();
		if ($record) {
			$this->changeLogModel->update($record, $this->initFillFromRequest($request));
			$record->update($this->initFillFromRequest($request));
			return JsonResponse::message(201, '');
		} else {
			$record = new WheelConfig();
			$record = $this->initFillFromRequest($request, $record);
			$record->save();
			$this->changeLogModel->create($record);
			return JsonResponse::message(201, '');
		}
	}

}
