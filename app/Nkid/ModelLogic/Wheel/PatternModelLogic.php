<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelPattern;
use App\Nkid\ModelLogic\BaseModelLogic;
use App\Nkid\ModelLogic\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PatternModelLogic extends BaseModelLogic {

	protected $relationships = ['prizes'];

	protected $searchColumn = ['name', 'alias'];

	private $changeLogModel;



	public const MODULE = 'PATTERN';

	public function newQuery() {
		$this->query = WheelPattern::query();
	}

	public function getModelReference() {
		$model = new WheelPattern();
		$this->fillable = $model->getFillable();
		unset($model);
		$this->changeLogModel = new ChangeLogModelLogic(self::MODULE);
	}

	public function store(Request $request): \Illuminate\Http\JsonResponse {
		$record = new WheelPattern();
		$record = $this->initFillFromRequest($request, $record);
		if ($record->save()) {
			$this->changeLogModel->create($record);
			$record->prizes()->sync(array_filter($request->prizes_location));
			return JsonResponse::message(201, $this->message('name', $record));
		}
		$this->changeLogModel->create($record, ChangeLogModelLogic::STATUS_FAILURE);
		return JsonResponse::message(500, $this->message('name', $record));

	}

	public function singleRecordUpdate($ids, Request $request): \Illuminate\Http\JsonResponse {
		$record = WheelPattern::find($ids);
		if ($record) {
			if (!$request->has('bonusIncrement') && !$request->has('bonusReset')) {
				$this->changeLogModel->update($record, $this->initFillFromRequest($request));
				$record->update($this->initFillFromRequest($request));
				if ($request->has('prizes_location')) {
                    $record->prizes()->sync(array_filter($request->prizes_location));
                }

				return JsonResponse::message(201, $this->message('name', $record));
			} else {
				$this->bonusIncrement($record, $request);
				$this->bonusReset($record, $request);
				return JsonResponse::message(201, '');
			}
		}
		$this->changeLogModel->update(null, $this->initFillFromRequest($request), ChangeLogModelLogic::STATUS_FAILURE);
		return JsonResponse::message(404, 'objectNotExist');
	}

	public function multipleRecordUpdate($ids, Request $request): \Illuminate\Http\JsonResponse {
		//
	}

	public function singleRecordDestroy($id): \Illuminate\Http\JsonResponse {
		$record = WheelPattern::find($id);
		$record->prizes()->detach($id);
		if ($record) {
			$record->delete();
			$this->changeLogModel->delete($record);
			return JsonResponse::message(200, $this->message('name', $record));
		}
		$this->changeLogModel->delete(null, ChangeLogModelLogic::STATUS_FAILURE);
		return JsonResponse::message(404, 'objectNotExist');
	}

	public function multipleRecordDestroy($ids): \Illuminate\Http\JsonResponse {
		$this->detachRelationships(
			$ids, function ($id) {
			$record = WheelPattern::find($id);
			if ($record) {
				$record->prizes()->detach($id);
				$this->changeLogModel->delete($record);
			}
		}
		);
		$count = WheelPattern::destroy($ids);
		return JsonResponse::message(200, $count);
	}


	private function bonusIncrement(Model $model, Request $request) {
		if ($request->has('bonusIncrement')) {
			$model->increment('bonus_current', (int)$request->get('bonusIncrement'));
		}
	}

	private function bonusReset(Model $model, Request $request) {
		if ($request->has('bonusReset')) {
		    $min = $model->bonus_min;
			$model->bonus_current = $min;
			$model->save();
		}
	}

}
