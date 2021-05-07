<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class PatternFacade extends Facade {

	protected static function getFacadeAccessor(): string {
		return 'wheel-pattern';
	}
}
