<?php

namespace BlahteSoftware\BetMath;

use BlahteSoftware\BetMath\Contracts\FootballMatch;
use BlahteSoftware\BetMath\Contracts\MatchList as ContractsMatchList;
use BlahteSoftware\BetMath\FootballMatch as AppFootballMatch;
use Exception;

final class MatchList extends Code implements ContractsMatchList {
    private array $items = [];
    private int $position = 0;

    public function __construct(FootballMatch ...$matches)
    {
        $this->items = (array) $matches;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public static function getFootballMatchesArrayFrom2DArray(array $data) : array {
        return array_reduce($data, function($prev, $curr){
            $prev[] = new AppFootballMatch(count($prev), $curr[0], $curr[1]);
            return $prev;
        }, []);
    }

    public function getThreeWayEvents() : array {
        return array_reduce($this->items, function($prev, $curr) {
            $prev = array_merge($prev, $curr->getThreeWayEvents());
            return $prev;
        }, []);
    }

    public function getFootballMatchFromEvent(string $event): FootballMatch
    {
        $parts = explode(AppFootballMatch::SYMBOL_SEPARATOR, $event);
        $leg = $parts[1];
        $footballMatch = array_filter($this->items, function($value) use ($leg) {
            return $value->getLeg() == $leg;
        });
        if(empty($footballMatch)) throw new Exception("Team Not Found!");
        return array_values($footballMatch)[0];
    }

    public function getBetOptionFromEvent(string $event) : string {
        $parts = explode(AppFootballMatch::SYMBOL_SEPARATOR, $event);
        $position = $parts[0];
        $footballMatch = $this->getFootballMatchFromEvent($event);
        switch($position) {
            case AppFootballMatch::HOME_SYMBOL:
                return '1';
            case AppFootballMatch::DRAW_SYMBOL:
                return 'X';
            case AppFootballMatch::AWAY_SYMBOL:
                return '2';
            default:
                throw new Exception("Failed to get a valid bet option!");
        }
    }

    public function getBetFromPermutation(string $permutation) : string {
        $events = explode(MatchList::COMBINATION_SEPARATOR, $permutation);
        $options = array_reduce($events, function ($prev, $curr) {
            $prev[] = $this->getBetOptionFromEvent($curr);
            return $prev;
        }, []);
        return implode(MatchList::COMBINATION_SEPARATOR, $options);
    }

    public function countThreeWayPermutations() : int {
        return pow(3, count($this));
    }

    public function getThreeWayCombinations() : array {
        $alpha = $this->getThreeWayEvents();
        $n = count($alpha);
        $m = count($this);
        $codes_integer = self::combination_integer($n, $m);
        $res = [];

        foreach($codes_integer as $code){
            $code_letter = [];
            foreach($code as $i => $c){
                $code_letter[$i] = $alpha[$c];
            }
            $res[] = implode(self::COMBINATION_SEPARATOR, $code_letter);
        }

        return $res;
    }

    public function getThreeWayPermutations() : array {
        $combinations = $this->getThreeWayCombinations();
        $res = [];
        foreach($combinations as $combination) {
            if(AppFootballMatch::eventsOfTheSameMatch(...explode(self::COMBINATION_SEPARATOR, $combination))) {
                continue;
            }
            $res[] = $combination;
        }
        return $res;
    }

    public function getThreeWayPermutationBets() : array {
        $permutations = $this->getThreeWayPermutations();
        $bets = array_map(function($permutation) {
            return $this->getBetFromPermutation($permutation);
        }, $permutations);
        return $bets;
    }

    public function getThreeWayPermutationBetsTable() : string {
        $headItems = [];
        foreach($this->items as $footballMatch) {
            $headItems[] = "{$footballMatch->getHomeTeam()} vs {$footballMatch->getAwayTeam()}";
        }
        $head = '<thead><tr><td>#</td><td>'. implode('</td><td>', $headItems) .'</td></tr></thead>';
        $bets = $this->getThreeWayPermutationBets();
        $bodyItems = [];
        foreach($bets as $key => $bet) {
            ++$key;
            $rowData = explode(self::COMBINATION_SEPARATOR, $bet);
            $bodyItems[] = "<tr><td>{$key}</td><td>". implode('</td><td>', $rowData) .'</td></tr>';
        }
        return "<table>{$head}<tbody></tbody>". implode('', $bodyItems) ."</table>";
    }
}