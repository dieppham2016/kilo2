<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class ConfigFacade extends Facade {

	protected static function getFacadeAccessor(): string {
		return 'wheel-config';
	}
}
