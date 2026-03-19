<?php

if (!function_exists('numToStr')) {
    function numToStr($num)
    {
        $ones = ['', 'бир', 'икки', 'уч', 'тўрт', 'беш', 'олти', 'етти', 'саккиз', 'тўққиз'];
        $tens = ['', 'ўн', 'йигирма', 'ўттиз', 'қирқ', 'эллик', 'олтмиш', 'етмиш', 'саксон', 'тўқсон'];
        $hundreds = ['', 'бир юз', 'икки юз', 'уч юз', 'тўрт юз', 'беш юз', 'олти юз', 'етти юз', 'саккиз юз', 'тўққиз юз'];

        if ($num == 0) {
            return 'ноль сўм';
        }

        $parts = [];

        // million
        $million = floor($num / 1000000);
        if ($million > 0) {
            $parts[] = trim(numToStr($million) . ' миллион');
            $num %= 1000000;
        }

        // thousand
        $thousand = floor($num / 1000);
        if ($thousand > 0) {
            $parts[] = trim(numToStr($thousand) . ' минг');
            $num %= 1000;
        }

        // hundreds
        $hundred = floor($num / 100);
        if ($hundred > 0) {
            $parts[] = $hundreds[$hundred];
            $num %= 100;
        }

        // tens & ones
        $ten = floor($num / 10);
        $one = $num % 10;

        if ($ten > 0) {
            $parts[] = $tens[$ten];
        }

        if ($one > 0) {
            $parts[] = $ones[$one];
        }

        return mb_strtolower(trim(implode(' ', array_filter($parts))), 'UTF-8');
    }
}
