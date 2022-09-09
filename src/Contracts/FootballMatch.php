<?php

namespace BlahteSoftware\BetMath\Contracts;

interface FootballMatch {
    const HOME_SYMBOL = 'H';
    const DRAW_SYMBOL = 'D';
    const AWAY_SYMBOL = 'A';
    const SYMBOL_SEPARATOR = '___';
    public function getLeg() : int;
    public function getHomeTeam() : string;
    public function getAwayTeam() : string;
    public function getThreeWayEvents() : array;
    public static function eventsOfTheSameMatch(string ...$events) : bool;
}