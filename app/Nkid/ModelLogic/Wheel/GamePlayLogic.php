<?php


namespace App\Nkid\ModelLogic\Wheel;


use Illuminate\Http\Request;
use wataridori\BiasRandom\BiasRandom;

class GamePlayLogic
{
    private $pattern;

    private $store;

    private $log;

    private $biasRandom;

    public function __construct()
    {
        $this->getModelReference();

    }

    private function getModelReference()
    {
        $this->pattern = new PatternModelLogic();
        $this->store = new StoreModelLogic();
        $this->biasRandom = new BiasRandom();
    }


    public function calculateDegrees(Request $request): \Illuminate\Http\JsonResponse
    {
        $pattern = $this->pattern->index($request);
        $prizes = $pattern[0]->prizes;
        $prizes_location = $pattern[0]->prizes_location;
        $prizesRandom = [];

        foreach ($prizes as $prize) {
            $prizesRandom[$prize->id] = $prize->rate;
        }
        $prizes_location[0] = null;
        $prizesRandom['bonus'] = $pattern[0]->bonus_rate;
        $this->biasRandom->setData($prizesRandom);
        $result = $this->biasRandom->random(); // id prize
        $aa = null;
        foreach ($prizes as $prize) {
            if ($prize->id == $result[0]) $aa = $prize;
        }
        $degrees = array_search($result[0] !== 'bonus' ? $aa->id : null, $prizes_location) * (360 / 12) + 1 + mt_rand(0, (360 / 12));
        return \App\Nkid\ModelLogic\JsonResponse::data('200', $degrees);
    }
}
