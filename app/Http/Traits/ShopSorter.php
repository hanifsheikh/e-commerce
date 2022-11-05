<?php

namespace App\Http\Traits;

trait ShopSorter
{
    public function sort($products, $type)
    {
        if ($type == 'high_to_low') {
            return $this->sortByHighToLow($products);
        }
        if ($type == 'low_to_high') {
            return $this->sortByLowToHigh($products);
        }
        if ($type == 'latest') {
            return $this->sortByLatest($products);
        }
        if ($type == 'highest_rating') {
            return $this->sortByHighestRating($products);
        }
    }
    public function sortByHighToLow($products)
    {
        usort($products, function ($a, $b) {
            return $a->minimum_offer_price < $b->minimum_offer_price;
        });
        return $products;
    }
    public function sortByLowToHigh($products)
    {
        usort($products, function ($a, $b) {
            return $a->minimum_offer_price > $b->minimum_offer_price;
        });
        return $products;
    }
    public function sortByLatest($products)
    {
        usort($products, function ($a, $b) {
            return $a->id < $b->id;
        });
        return $products;
    }
    public function sortByHighestRating($products)
    {
        usort($products, function ($a, $b) {
            return $a->ratings < $b->ratings;
        });
        return $products;
    }
}
