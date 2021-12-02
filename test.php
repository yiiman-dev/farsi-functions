<?php
/**
 * @date_of_create: 12/2/2021 AD 11:08
 */

use YiiMan\functions\functions;

require 'vendor/autoload.php';
define('N', "\n\n\n");
$f = new functions();
echo 'convert '.date('Y-m-d ').'00:00:00'.' to date (clear_zerotime method):'.$f->clear_zerotime(date('Y-m-d '.'00:00:00')).N;
echo 'convert '.date('Y-m-d H:i:s').' to jalali (convert_dateTime method):'.$f->convert_dateTime(date('Y-m-d H:i:s')).N;
echo 'convert '.date('Y-m-d').' to jalali (convert_date method):'.$f->convert_date(date('Y-m-d')).N;
echo 'convert `2017-2018` to jalali (yearsToShamsi method):'.$f->yearsToShamsi('2017-2018').N;
echo 'convert `2017` to jalali (YearToShamsi method):'.$f->YearToShamsi('2017').N;
echo 'convert `1396` to gregory (YearToGregorian method):'.$f->YearToGregorian('1396').N;
echo 'convert price to text ->`256700` with toman unit and upper roun (priceText method):'.$f->priceText(256700,'تومان','up').N;
echo 'convert digits to persian `1234567890` (convertDigit method):'.$f->convertDigit('1234567890').N;
echo 'convert gregorian date-time to persian descriptive string `2019-12-01 12:22:00` (descriptive_date method):'.$f->descriptive_date('2019-12-01 12:22:00').N;
echo 'convert day counts to days|months|years count (day2Text method):'.$f->day2Text(365).N;
echo 'different days between two date (differenceDateDay method):'.$f->differenceDateDay('2017-12-21','2017-10-21').N;
echo 'different hours between two date (differenceHour method):'.$f->differenceHour('2017-12-21','2017-10-21').N;
echo 'different years between two date (differenceDateYear method):'.$f->differenceDateYear('2016-12-21','2017-12-21').N;
echo 'manipulicate date (manipulicateDate method):'.$f->manipulicateDate('2016-12-21','+2 days').N;
echo 'manipulicateDateTime date-time (manipulicateDateTime method):'.$f->manipulicateDateTime('2016-12-21 12:30:23','+2 hours').N;
echo 'limit string (limitText method):'.$f->limitText('Hi! i am YiiMan, i am php developer',20).N;
echo 'return client ip (getClientIP method):'.$f->getClientIP().N;
echo 'return percent of your integer (percent2NumberCalculator method):'.$f->percent2NumberCalculator(1000,50).N;
echo 'return changes between two number as percent (number2percentCalculator method):'.$f->number2percentCalculator(500,2000).N;
