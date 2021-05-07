<?php


namespace App\Nkid\ModelLogic;


use App\Nkid\Contracts\ModelLogic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;
use RuntimeException;
use Closure;

class BaseModelLogic implements ModelLogic {

	/**
	 * Query
	 * @type Builder
	 * */
	protected $query;

	/**
	 *    Relationship will get
	 * @type array
	 * */
	protected $relationships;

	/**
	 *    fillable model
	 * @type array
	 * */
	protected $fillable = [];

	/**
	 *    columns to searching
	 * @type array
	 * */
	protected $searchColumn = [];

	/**
	 *  Column guard
	 * @type array
	 * */
	protected $guard = [];

	/**
	 *  Conditions get specific columns
	 * @type array
	 * */
	protected $onlyConditions = [];

	public function __construct() {
		$this->newQuery();
		$this->getModelReference();
	}

	public function newQuery() {
		throw new RuntimeException('ModelLogic does not implement newQuery method.');
	}

	public function getModelReference() {
		throw new RuntimeException('ModelLogic does not implement getModelReference method.');
	}


	/**
	 * Return collection by condition
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection {
		$this->query = $this->only($request);
		$this->query = $this->getPrizeByArrayId($request);
		$this->query = $this->search($request);
		return $this->getCollection($request);
	}

	/**
	 *    Insert new record to database
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request): JsonResponse {
//		throw new RuntimeException('ModelLogic does not implement store method.');
	}

	/**
	 *    Return Collection
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function getCollection(Request $request): AnonymousResourceCollection {
		$limit = Arr::get($request->all(), 'limit');
		$this->query = $this->relationship();
		if ($limit) {
			$result = $this->query->paginate($limit);
			$this->newQuery();
			return \App\Nkid\ModelLogic\JsonResponse::paginate(200, JsonResource::collection($result));
		} else {
			$result = $this->query->get();
			$this->newQuery();
			return \App\Nkid\ModelLogic\JsonResponse::collection(200, JsonResource::collection($result));
		}
	}


	/**
	 * Search in Model
	 *
	 * @param Request $request
	 * @return Builder
	 */
	protected function search(Request $request): Builder {
		$keyword = Arr::get($request->all(), 'searchKeyword');
		if ($keyword) {
			return $this->query->whereLike($this->searchColumn, $keyword);
		}
		return $this->query;
	}


	/**
	 *    Update record
	 *
	 * @param $ids
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function singleRecordUpdate($ids, Request $request): JsonResponse {
//		throw new RuntimeException('ModelLogic does not implement singleRecordUpdate method.');
	}

	/**
	 *    Update collection
	 *
	 * @param array | int $ids
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function multipleRecordUpdate($ids, Request $request): JsonResponse {
//		throw new RuntimeException('ModelLogic does not implement multipleRecordUpdate method.');
	}

	/**
	 *    Destroy record
	 *
	 * @param $id
	 * @return JsonResponse
	 */
	public function singleRecordDestroy($id): JsonResponse {
//		throw new RuntimeException('ModelLogic does not implement singleRecordDestroy method.');
	}

	/**
	 *    Destroy collection
	 *
	 * @param array | int $ids
	 * @return JsonResponse
	 */
	public function multipleRecordDestroy($ids): JsonResponse {
//		throw new RuntimeException('ModelLogic does not implement multipleRecordDestroy method.');
	}


	/**
	 * Create a fill when storing a new prize
	 *
	 * @param $key
	 * @param Request $request
	 * @return string | null | array
	 */
	protected function createFillFromRequest($key, Request $request) {
		$type = $request->type;
		if ($type == 'Ticket' && $key == 'name') {
			return $request->amount . ' Ticket';
		}
		return $request->get($key);
	}

	/**
	 * Relationship
	 *
	 * @return Builder
	 */
	protected function relationship(): Builder {
		if ($this->relationships) {
			if (is_array($this->relationships)) {
				foreach ($this->relationships as $relationship) {
					$this->query->with($relationship);
				}
			}
		}
		return $this->query;
	}

	protected function detachRelationships($ids, Closure $callback) {
		if (is_array($ids)) {
			foreach ($ids as $id) {
				$callback($id);
			}
		} else {
			$callback($ids);
		}
	}

	/**
	 *    Get Prize by array ID
	 *
	 * @param Request $request
	 * @return Builder
	 */
	protected function getPrizeByArrayId(Request $request): Builder {
		$ids = Arr::get($request->all(), 'ids');
		$search = Arr::get($request->all(), 'searchKeyword');
		if ($ids && is_array($ids) && !$search) {
			return $this->query->whereIn('id', $ids);
		}
		return $this->query;
	}

	/**
	 *
	 *
	 *
	 * @param array $fill
	 * @param Model|null $model
	 * @return Model | array
	 */
	protected function initFill(array $fill, Model $model = null) {
		if ($model) {
			foreach ($this->fillable as $value) {
				if (!in_array($value, $this->guard, TRUE)) {
					$model->setAttribute($value, Arr::get($fill, $value));
				}
			}
			return $model;
		} else {
			$fill = [];
			foreach ($this->fillable as $value) {
				if (!in_array($value, $this->guard, TRUE)) {
					$fill[$value] = Arr::get($fill, $value);
				}
			}
			return $fill;
		}
	}

	/**
	 *
	 *
	 *
	 * @param Request $request
	 * @param Model|null $model
	 * @return Model | array
	 */
	protected function initFillFromRequest(Request $request, Model $model = null) {
		if ($model) {
			foreach ($this->fillable as $value) {
				if ($request->has($value) && !in_array($value, $this->guard, TRUE)) {
					$model->setAttribute($value, $this->createFillFromRequest($value, $request));
				}
			}
			return $model;
		} else {
			$fill = [];
			foreach ($this->fillable as $value) {
				if ($request->has($value) && !in_array($value, $this->guard, TRUE)) {
					$fill[$value] = $this->createFillFromRequest($value, $request);
				}

			}
			return $fill;
		}
	}

	/**
	 *    Message will return for client
	 *
	 * @param string $key
	 * @param Model $model
	 * @return mixed
	 */
	protected function message(string $key, Model $model) {
		return $model->getAttribute($key);
	}

	protected function only(Request $request): Builder {
		if ($request->has('only')) {
			return $this->query->select($request->get('only'));
		}
		return $this->query;
	}

}

