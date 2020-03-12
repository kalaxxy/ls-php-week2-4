<?php
include "config.php";

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('ignore_repeated_errors', 0);

interface iTariff
{
    public function getAgeRatio($age);
    public function priceCalc($kms, $mins, $age);
}

abstract class Tariff implements iTariff
{
    public $pricePerKm;
    public $pricePerTime;

    public function getAgeRatio($age)
    {
        if ($age >= 18 && $age <= 21) {
            $ageRatio = 1.1;
        } elseif ($age > 21 && $age <= 65) {
            $ageRatio = 1;
        } else {
            throw new Exception("Возраст должен быть от 18 до 65 лет");
        }

        return $ageRatio;
    }

    abstract function priceCalc($kms, $mins, $age);
}

trait Details
{
    public function getGps(bool $gps = false, $mins)
    {
        if (!$gps) {
            return null;
        }
        $roundedHour = ceil($mins / MIN_PER_HOUR);
        return PRICE_GPS * $roundedHour;
    }

    public function addDriver(bool $driver = false)
    {
        if (!$driver) {
            return null;
        }

        return PRICE_DRIVER;
    }
}

class TariffBase extends Tariff
{
    public $pricePerKm = 10;
    public $pricePerTime = 3;

    use Details;

    public function priceCalc($kms, $mins, $age, bool $gps = false)
    {
        $sum = ($this->pricePerKm * $kms + $this->pricePerTime * $mins) * $this->getAgeRatio($age) + $this->getGps($gps, $mins);
        echo $sum;
    }
}


class TariffHour extends Tariff
{
    public $pricePerKm = 0;
    public $pricePerTime = 200;

    use Details;

    public function priceCalc($kms, $mins, $age, bool $gps = false, bool $driver = false)
    {
        $roundedHour = ceil($mins / MIN_PER_HOUR);
        echo "Часов: " . $roundedHour . "<br>";
        $sum = ($this->pricePerKm * $kms + $this->pricePerTime * $roundedHour) * $this->getAgeRatio($age) + $this->getGps($gps, $mins) + $this->addDriver($driver);
        echo $sum;
    }
}

class TariffDay extends Tariff
{
    public $pricePerKm = 1;
    public $pricePerTime = 1000;

    use Details;

    public function priceCalc($kms, $mins, $age, bool $gps = false, bool $driver = false)
    {
        $roundedDay = ceil($mins / MIN_RER_DAY);
        echo "Суток: " . $roundedDay . "<br>";
        $sum = ($this->pricePerKm * $kms + $this->pricePerTime * $roundedDay) * $this->getAgeRatio($age) + $this->getGps($gps, $mins) + $this->addDriver($driver);
        echo $sum;
    }
}

class TariffStudent extends Tariff
{
    public $pricePerKm = 4;
    public $pricePerTime = 1;

    use Details;

    public function priceCalc($kms, $mins, $age, bool $gps = false)
    {
        if ($age >= 25) {
            throw new Exception("Студенческий тариф действителен для возраста 18-25 лет");
        } else {
            $sum = ($this->pricePerKm * $kms + $this->pricePerTime * $mins) * $this->getAgeRatio($age) + $this->getGps($gps, $mins);
            echo $sum;
        }
    }
}
