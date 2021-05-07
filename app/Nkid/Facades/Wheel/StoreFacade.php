<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class StoreFacade extends Facade {

	protected static function getFacadeAccessor(): string {
		return 'wheel-store';
	}
}
