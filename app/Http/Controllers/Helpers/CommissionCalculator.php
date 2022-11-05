<?php

namespace App\Http\Controllers\Helpers;

class CommissionCalculator
{

    private static $previous_sale;
    private static $current_sale;
    private static $total;
    private static $commission_rate;
    private static $scales = [0, 1, 2, 3, 4];
    private static $instance = null;

    //Singleton instance create
    public static function open()
    {
        if (self::$instance == null) {
            self::$instance = new CommissionCalculator();
        }
        return self::$instance;
    }

    private static function total_commission()
    {
        $carry = 0;
        foreach (self::$scales as $scale) {
            $carry += ($scale * 1000);
            if ($scale === 0) {
                if ((self::$total >= $scale * 100000) && (self::$total <= (($scale + 1) * 100000))) {
                    self::$commission_rate = $scale + 1;
                    return self::$total * (($scale + 1) / 100);
                }
            } else {
                if ((self::$total > $scale * 100000) && (self::$total <= (($scale + 1) * 100000))) {
                    self::$commission_rate = $scale + 1;
                    return $carry + (self::$total - ($scale * 100000)) * (($scale + 1) / 100);
                } else {
                    if (self::$total > 400000) {
                        self::$commission_rate = 5;
                        return 10000 + (self::$total - 400000) * 0.05;
                    }
                }
            }
        }
    }
    private static function previous_commission()
    {
        $carry = 0;
        foreach (self::$scales as $scale) {
            $carry += ($scale * 1000);
            if ($scale === 0) {
                if ((self::$previous_sale >= $scale * 100000) && (self::$previous_sale <= (($scale + 1) * 100000))) {
                    return self::$previous_sale * (($scale + 1) / 100);
                }
            } else {
                if ((self::$previous_sale > $scale * 100000) && (self::$previous_sale <= (($scale + 1) * 100000))) {
                    return $carry + (self::$previous_sale - ($scale * 100000)) * (($scale + 1) / 100);
                } else {
                    if (self::$previous_sale > 400000) {
                        return 10000 + (self::$previous_sale - 400000) * 0.05;
                    }
                }
            }
        }
    }
    public static function calculate($previous_sale, $current_sale)
    {
        self::$previous_sale = $previous_sale;
        self::$current_sale = $current_sale;
        self::$total = self::$previous_sale + self::$current_sale;
        return [
            'total_commission' => self::total_commission(),
            'current_commission' => self::total_commission() - self::previous_commission(),
            'commission_rate' => self::$commission_rate
        ];
    }
}
