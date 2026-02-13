<?php
namespace Partarum\Helper\Date {

    use Carbon\Carbon;

    class DateCalculator extends Carbon {

        public static function isNegative(int | float $number) : bool {

            return ($number < 0);
        }
    }
}