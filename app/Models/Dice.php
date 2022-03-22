<?php

namespace App\Models;

class Dice
{
    protected $faces = [1, 2, 3, 4, 5, 6];
    public $topValue;

    public function roll()
    {
        $randomKey = array_rand($this->faces, 1);
        $this->topValue = $this->faces[$randomKey];
        return $this->topValue;
    }

    public function getTopValue()
    {
        return $this->topValue;
    }

    public function getFaces()
    {
        return $this->faces;
    }

    public function run()
    { 
        $sum = 0;
        $numberToGetBelowPrimeSum = 2000000;

        for ($number = 2; $number <= $numberToGetBelowPrimeSum; $number++) {
            if ($this->isPrime($number)) {
                $sum += $number;
            }
        }

        var_dump($sum);

    }

    public function isPrime($number) 
    {
        //1 is not prime. See: http://en.wikipedia.org/wiki/Prime_number#Primality_of_one
        if($number == 1) {
            return false;
        }

        //2 is prime (the only even number that is prime)
        if($number == 2) {
            return true;
        }

        /**
         * if the number is divisible by two, then it's not prime and it's no longer
         * needed to check other even numbers
         */
        if($number % 2 == 0) {
            return false;
        }

        /**
         * Checks the odd numbers. If any of them is a factor, then it returns false.
         * The sqrt can be an aproximation, hence just for the sake of
         * security, one rounds it to the next highest integer value.
         */
        for($i = 3; $i <= ceil(sqrt($number)); $i = $i + 2) {
            if($number % $i == 0)
                return false;
        }

        return true;
    }
}
