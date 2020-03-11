<?php
include "class.tariff.php";

echo "<div>Тариф базовый:</div>";
$tariffBase = new TariffBase;
$tariffBase->priceCalc(40, 20, 20, true);

echo "<div>Тариф почасовой:</div>";
$tariffHour = new TariffHour;
$tariffHour->priceCalc(40, 20, 20, true);

echo "<div>Тариф дневной:</div>";
$tariffDay = new TariffDay;
$tariffDay->priceCalc(40, 20, 20, true);

echo "<div>Тариф студенческий:</div>";
$tariffStudent = new TariffStudent;
$tariffStudent->priceCalc(40, 20, 20, true);