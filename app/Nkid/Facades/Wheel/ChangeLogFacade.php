<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class ChangeLogFacade extends Facade {

	protected static function getFacadeAccessor(): string {
		return 'wheel-change-log';
	}
}
