<?php

class letterpair
{
    private static $a = 'аеєиіїоуюя';
    private static $b = 'бвгґджзйклмнпрстфхцчшщ';

    public static function get(int $seed)
    {
        $res = mb_substr(self::$b, rand(0, mb_strlen(self::$b) * $seed) % mb_strlen(self::$b), 1);
        if (rand(0, $seed) % 6 > 0) {
            $res .= mb_substr(self::$a, rand(0, mb_strlen(self::$a) * $seed) % mb_strlen(self::$a), 1);
        }
        return $res;
    }
}

class word
{
    public static function get(int $seed, int $length = 3)
    {
        $res = '';
        for ($i = 0; $i < $length; $i++) {
            $res .= letterpair::get($seed);
        }
        return $res;
    }
}

class sentence
{
    private static $punctuation = [
        '$0, ',
        '$0, - ',
        '$0 - ',
        '$0... ',
        '"$0" ',
        '($0) '
    ];

    public static function get(int $seed, int $words_count = 7, int $word_max_length = 7, float $punctuation_chance = 0.1)
    {
        $res = '';
        $res = ucfirst(word::get($seed, rand(1, $word_max_length))) . ' ';
        for ($i = 1; $i < $words_count; $i++) {
            $word = word::get($seed, rand(1, $word_max_length));
            if (rand(0, 100) < $punctuation_chance * 100) {
                $word = str_replace('$0', $word, self::$punctuation[rand(0, count(self::$punctuation) - 1)]);
            } else {
                $word .= ' ';
            }
            $res .= $word;
        }
        return trim($res) . '.';
    }
}

echo sentence::get(rand(0, 100), rand(1, 10), 4, 0.2);