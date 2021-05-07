<?php


namespace App\Nkid\ModelLogic\Wheel;


use App\Models\Wheel\WheelChangeLog;
use App\Nkid\ModelLogic\BaseModelLogic;
use Auth;

class ChangeLogModelLogic extends BaseModelLogic {

	public const CREATE = 'CREATE';
	public const UPDATE = 'UPDATE';
	public const DELETE = 'DELETE';

	public const STATUS_SUCCESS = 'Success';
	public const STATUS_FAILURE = 'Failure';

	protected $searchColumn = ['user_name', 'created_at', 'updated_at'];

	private $logModule;

	public function __construct($module = null) {
		$this->newQuery();
		$this->getModelReference();
		$this->logModule = $module;
	}

	public function newQuery() {
		$this->query = WheelChangeLog::query();
	}

	public function getModelReference() {
		$model = new WheelChangeLog();
		$this->fillable = $model->getFillable();
		unset($model);
	}

	public function create($to, $status = self::STATUS_SUCCESS) {
		$this->log(Auth::user()->name, null, $to, self::CREATE, $status);
	}

	public function update($from, $to, $status = self::STATUS_SUCCESS) {
		$this->log(Auth::user()->name, $from, $to, self::UPDATE, $status);
	}

	public function delete($from, $status = self::STATUS_SUCCESS) {
		$this->log(Auth::user()->name, $from, null, self::DELETE, $status);
	}

	private function log($userName, $from, $to, $type, $status = self::STATUS_SUCCESS) {
		$record = new WheelChangeLog();
		$record = $this->initFill(
			[
				'user_name' => $userName,
				'module' => $this->logModule,
				'from' => $from,
				'to' => $to,
				'action' => $type,
				'status' => $status
			], $record
		);
		$record->save();
	}
}
