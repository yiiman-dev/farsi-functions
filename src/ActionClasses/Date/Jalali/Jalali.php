<?php

namespace YiiMan\functions\ActionClasses\Date\Jalali;


use YiiMan\functions\ActionClasses\Date\Jalali\Actions\Date;


class Jalali
{

    public function now():Date{
        return new Date(date('Y-m-d H:i:s'));
    }

}