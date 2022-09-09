<?php

namespace BlahteSoftware\BetMath\Contracts;

use Countable;
use Iterator;

interface MatchList extends Iterator, Countable {
    const COMBINATION_SEPARATOR = ' ';
    public static function getFootballMatchesArrayFrom2DArray(array $data) : array;
    public function getThreeWayEvents() : array;
    public function getFootballMatchFromEvent(string $event) : FootballMatch;
    public function getBetOptionFromEvent(string $event) : string;
    public function getBetFromPermutation(string $permutation) : string;
    public function getThreeWayPermutationBetsTable() : string;
    public function countThreeWayPermutations() : int;
    public function getThreeWayCombinations() : array;
    public function getThreeWayPermutations() : array;
    public function getThreeWayPermutationBets() : array;
}