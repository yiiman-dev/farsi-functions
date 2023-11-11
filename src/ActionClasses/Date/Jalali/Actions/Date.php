<?php

namespace YiiMan\functions\ActionClasses\Date\Jalali\Actions;

use YiiMan\functions\Libraries\DateConverter;

class Date
{
    private $_date;
    private $_exploded;
    private DateConverter $_converter;
    public function __construct(string $date)
    {
        $this->_date=$date;
        $this->_converter=new DateConverter();
    }

    public function date($delimiter='/'){
        return $this->_converter->convert_date($this->_date,'jalali',$delimiter);
    }

    public function datetime($delimiter='/'){
        return $this->_converter->convert_dateTime($this->_date,'jalali',$delimiter);
    }

    public function year(){
        return $this->_exploded[0];
    }

    public function month(){
        return $this->_exploded[1];
    }

    public function day(){
        return $this->_exploded[2];
    }

}