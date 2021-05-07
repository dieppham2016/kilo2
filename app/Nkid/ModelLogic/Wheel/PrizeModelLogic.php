<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelPrize;
use App\Nkid\ModelLogic\BaseModelLogic;
use App\Nkid\ModelLogic\JsonResponse;
use Illuminate\Http\Request;

class PrizeModelLogic extends BaseModelLogic {

	protected $relationships = ['prizePatterns'];

	protected $searchColumn = ['name', 'more_info', 'type'];

	private $changeLogModel;

	public const MODULE = 'PRIZE';

	public function newQuery() {
		$this->query = WheelPrize::query();
	}

	public function getModelReference() {
		$model = new WheelPrize();
		$this->fillable = $model->getFillable();
		unset($model);
		$this->changeLogModel = new ChangeLogModelLogic(self::MODULE);
	}

	/**
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request): \Illuminate\Http\JsonResponse {
		$record = new WheelPrize();
		$record = $this->initFillFromRequest($request, $record);
//		$record->save();
		if ($record->save()) {
			$this->changeLogModel->create($record);
			return JsonResponse::message(201, $this->message('name', $record));
		}
		else {
			$this->changeLogModel->create($record, WheelChangeLogLogic::STATUS_FAILURE);
			return JsonResponse::message(500, $this->message('name', $record));
		}
	}


	/**
	 *
	 *
	 * @param $ids
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function singleRecordUpdate($ids, Request $request): \Illuminate\Http\JsonResponse {
		$record = WheelPrize::find($ids);
		if ($record) {
			$record->update($this->initFillFromRequest($request));
			$this->changeLogModel->update($record, $this->initFillFromRequest($request));
			return JsonResponse::message(201, $this->message('name', $record));
		}
		$this->changeLogModel->update(null, $this->initFillFromRequest($request), ChangeLogModelLogic::STATUS_FAILURE);
		return JsonResponse::message(404, 'objectNotExist');
	}


	/**
	 *
	 *
	 * @param $ids
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function multipleRecordUpdate($ids, Request $request): \Illuminate\Http\JsonResponse {
		//
	}


	/**
	 *
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function singleRecordDestroy($id): \Illuminate\Http\JsonResponse {
		$record = WheelPrize::find($id);
		$record->prizePatterns()->detach($id);
		if ($record) {
			$record->delete();
			$this->changeLogModel->delete($record);
			return JsonResponse::message(200, $this->message('name', $record));
		}
		$this->changeLogModel->delete($record, ChangeLogModelLogic::STATUS_FAILURE);
		return JsonResponse::message(404, 'objectNotExist');
	}

	/**
	 *
	 *
	 * @param $ids
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function multipleRecordDestroy($ids): \Illuminate\Http\JsonResponse {
		$this->detachRelationships($ids, function ($id) {
			$record = WheelPrize::find($id);
			if ($record) {
				$record->prizePatterns()->detach($id);
				$this->changeLogModel->delete($record);
			}
		});
		$count = WheelPrize::destroy($ids);
		return JsonResponse::message(200, $count);
	}

}
