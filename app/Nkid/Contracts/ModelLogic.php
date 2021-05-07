<?php


namespace App\Nkid\Contracts;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface ModelLogic {

	/**
	 * Return collection by condition
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection;

	/**
	 *	Insert new record to database
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request): JsonResponse;

	/**
	 *    Return Collection
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function getCollection(Request $request): AnonymousResourceCollection;


	/**
	 *	Update record
	 *
	 * @param $id
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function singleRecordUpdate($id, Request $request): JsonResponse;


	/**
	 *	Update collection
	 *
	 * @param array | int $ids
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function multipleRecordUpdate($ids, Request $request): JsonResponse;


	/**
	 *	Destroy record
	 *
	 * @param $id
	 * @return JsonResponse
	 */
	public function singleRecordDestroy($id): JsonResponse;

	/**
	 *	Destroy collection
	 *
	 * @param array | int $ids
	 * @return JsonResponse
	 */
	public function multipleRecordDestroy($ids): JsonResponse;

}
