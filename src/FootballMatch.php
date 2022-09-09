<?php

namespace BlahteSoftware\BetMath;

use BlahteSoftware\BetMath\Contracts\FootballMatch as ContractsFootballMatch;

class FootballMatch implements ContractsFootballMatch {
    private string $homeTeam;
    private string $awayTeam;
    private int $leg;

    public function __construct(int $leg, string $homeTeam, string $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->leg = $leg;
    }

    public function getLeg() : int {
        return $this->leg;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
    {
        return $this->awayTeam;
    }

    public function getThreeWayEvents(): array
    {
        return [
            self::HOME_SYMBOL . self::SYMBOL_SEPARATOR . $this->leg,
            self::DRAW_SYMBOL . self::SYMBOL_SEPARATOR . $this->leg,
            self::AWAY_SYMBOL . self::SYMBOL_SEPARATOR . $this->leg
        ];
    }

    public static function eventsOfTheSameMatch(string ...$events) : bool {
        $events = (array) $events;
        $legs = array_reduce($events, function($prev, $curr) {
            $prev[] = explode(self::SYMBOL_SEPARATOR, $curr)[1];
            return $prev;
        }, []);
        return count($legs) !== count(array_unique($legs));
    }
}