<?php

namespace YiiMan\functions\ActionClasses\Date;

use YiiMan\functions\ActionClasses\Date\Jalali\Jalali;
use YiiMan\functions\ActionClasses\Gregorian;

class Date
{

    /**
     * توابع کار با تاریخ شمسی
     * @return Jalali
     */
    public function jalali():Jalali{
        return new Jalali();
    }

    /**
     * توابع کار با تاریخ میلادی
     * @return Gregorian
     */
    public function gregorian():Gregorian{
        return new Gregorian();
    }
}