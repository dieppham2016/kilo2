<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class GameLogFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'wheel-game-log';
    }
}
