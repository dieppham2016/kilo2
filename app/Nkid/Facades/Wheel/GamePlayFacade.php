<?php


namespace App\Nkid\Facades\Wheel;


use Illuminate\Support\Facades\Facade;

class GamePlayFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'wheel-game-play';
    }
}
