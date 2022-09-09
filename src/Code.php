<?php

namespace BlahteSoftware\BetMath;

class Code {
    protected static function combinations_set($set = [], $size = 0) {
        if ($size == 0) return [[]];
        if ($set == []) return [];
        $prefix = [array_shift($set)];
        $result = [];

        foreach (self::combinations_set($set, $size-1) as $suffix) {
            $result[] = array_merge($prefix, $suffix);
        }

        foreach (self::combinations_set($set, $size) as $next) {
            $result[] = $next;
        }

        return $result;
    }

    protected static function combination_integer($n, $m) {
        return self::combinations_set(range(0, $n-1), $m);
    }

    /**
     * Generates a list of combinations of letters of the alphabet
     * of required size.
     *
     * @param int $m The desired number of characters in each code.
     *
     * @return array Returns an array of $m-letter codes
     */
    public static function combination_alphabet($m = 4){
        $alpha = range('A', 'Z');
        $n = count($alpha);
        $codes_integer = self::combination_integer($n, $m);
        $res = [];

        foreach($codes_integer as $code){
            $code_letter = [];
            foreach($code as $i => $c){
                $code_letter[$i] = $alpha[$c];
            }
            $res[] = implode('', $code_letter);
        }

        return $res;
    }

    /**
     * Generates a list of combinations of letters of the alphabet
     * of required size.
     *
     * @param int $m The desired number of characters in each code.
     *
     * @return array Returns an array of $m-letter codes.
     * Each code is an array with a name key.
     */
    public static function combination_alphabet_name_key($m = 4){
        $alpha = range('A', 'Z');
        $n = count($alpha);
        $codes_integer = self::combination_integer($n, $m);
        $res = [];

        foreach($codes_integer as $code){
            $code_letter = [];
            foreach($code as $i => $c){
                $code_letter[$i] = $alpha[$c];
            }
            $res[] = ['name' => implode('', $code_letter)];
        }

        return $res;
    }
}
