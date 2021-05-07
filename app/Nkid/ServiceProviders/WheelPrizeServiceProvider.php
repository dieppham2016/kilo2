<?php

namespace App\Nkid\ServiceProviders;

use App;
use Illuminate\Support\ServiceProvider;

class WheelPrizeServiceProvider extends ServiceProvider {
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		App::bind(
			'wheel-prize', function () {
			return new App\Nkid\ModelLogic\Wheel\PrizeModelLogic;
		});

		App::bind(
			'wheel-pattern', function () {
			return new App\Nkid\ModelLogic\Wheel\PatternModelLogic;
		});

		App::bind(
			'wheel-change-log', function () {
			return new App\Nkid\ModelLogic\Wheel\ChangeLogModelLogic;
		});

		App::bind(
			'wheel-config', function () {
			return new App\Nkid\ModelLogic\Wheel\ConfigModelLogic;
		});

		App::bind(
			'wheel-store', function () {
			return new App\Nkid\ModelLogic\Wheel\StoreModelLogic;
		});

		App::bind(
			'wheel-game-play', function () {
			return new App\Nkid\ModelLogic\Wheel\GamePlayLogic;
		});

		App::bind(
			'wheel-game-log', function () {
			return new App\Nkid\ModelLogic\Wheel\GameLogModelLogic;
		});


	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		//
	}
}
