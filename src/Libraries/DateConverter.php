<?php

namespace YiiMan\functions\Libraries;

use YiiMan\functions\Libraries\JDF;
class DateConverter
{

    /**
     * Will Change yyyy-mm-dd hh:ii:ss from gregorian to shmsi(jalali)
     * @param $in_datetime
     * @param  string  $delimiter
     * @param  string  $target jalali | gregory
     * @return string will return string date and if DateTime was =='0000-00-00 00:00:00' will return '---'
     * @todo debug delimiter
     */
    public function convert_dateTime($in_datetime,$target = 'jalali', $delimiter = '-')
    {
        if ($in_datetime && $in_datetime != '0000-00-00 00:00:00') {
            $dateExplited = explode(' ', $in_datetime);
            $out= $this->convert_date($dateExplited[0],  $target,$delimiter).(!empty($dateExplited[1])?' '.$dateExplited[1]:'');
            return $out;
        }

        return '---';
    }

    /**
     * check your jalali date and clear 00:00:00 text from it and return :
     * yy:mm:dd as string
     */
    public function clear_zerotime($date)
    {
        if ($date == '' or $date == null) {
            return '';
        }

        $datetime = explode('-', $date);

        if (count($datetime) < 3) {
            return '';

        }
        if (
            count(explode(' ', $datetime[2])) < 2 or count(
                explode(' ', $datetime[2])
            ) < 2
        ) {
            return '';
        }
        //echo jdf::gregorian_to_jalali($datetime[0], $datetime[1], explode(' ', $datetime[2])[0], '/') .
        //   ' ' . explode(' ', $datetime[2])[1];
        $var1 = serialize(
            JDF::gregorian_to_jalali(
                $datetime[0],
                $datetime[1],
                explode(' ', $datetime[2])[0],
                '/'
            ).
            ' '.explode(' ', $datetime[2])[1]
        );
        $var2 = str_replace('\'', '', $var1);
        $var3 = str_replace('00:00:00', '', $var2);
        if (!($var2 === $var3)) {

            $var4 = str_replace('s:17', 's:09', $var3);
            $var5 = str_replace('s:18', 's:10', $var4);
            $var6 = str_replace('s:19', 's:11', $var5);
            $var7 = unserialize($var6);

            return str_replace('00:00:00', '', $var7);

        } else {
            return $var3 = str_replace('00:00:00', '', unserialize($var1));
        }
    }

    /**
     * @param          $in_date
     * @param  string  $target  jalali|gregory
     * @param  string  $delimiter
     * @return mixed|null|string
     */
    public function convert_date($in_date, string $target = 'jalali', string $delimiter = '/')
    {
        if ($target === 'jalali') {
            if ($in_date) {
                if (strlen($in_date) > 10) {
                    $datetime = explode(' ', $in_date);
                    $in_date = str_replace($delimiter, '/', $datetime[0]);
                } else {
                    $in_date = str_replace($delimiter, '/', $in_date);
                }
                if ($in_date == '0000-00-00') {
                    return null;
                }
                $converted=JDF::jdate('Y/m/d', strtotime($in_date));
                $out= str_replace('/', $delimiter,$converted );
                return $out;
            }

            return null;
        } else {
            if ($target === 'gregory') {
                if ($in_date && $in_date != '0000-00-00') {
                    if (strlen($in_date) > 10) {
                        $datetime = explode(' ', $in_date);
                        $in_date = str_replace($delimiter, '/', $datetime[0]);
                    } else {
                        $in_date = str_replace($delimiter, '/', $in_date);
                    }
                    $jdate = explode('/', $in_date);
                    if (count($jdate)) {

                        return implode(
                            $delimiter,
                            JDF::jalali_to_gregorian(
                                $jdate[0],
                                $jdate[1],
                                $jdate[2]
                            )
                        );
                    }
                }

                return null;
            }
        }
    }

    /**
     * @param $years string example input 2017-2018
     * @return string
     */
    public function yearsToShamsi(string $years)
    {
        $years = explode('-', $years);
        $years[0] = (string) (substr($this->convert_date($years[0].'-10-12', 'jalali'), 0, 4));
        $years[1] = substr($this->convert_date($years[1].'-10-12', 'jalali'), 0, 4);

        return $years[0].'-'.$years[1];
    }

    /**
     * @param $year string example 2017
     * @return string
     */
    public function YearToShamsi($year)
    {
        $year = $year.'-10-12';
        $year = substr($this->convert_date($year, 'jalali'), 0, 4);

        return $year;
    }

    public function YearToGregorian($year)
    {
        $year = $year.'-09-12';
        $year = substr($this->convert_date($year, 'gregory', '-'), 0, 4);

        return $year;
    }

    /**
     * نام روز هفته را با دریافت شماره ی آن بازگردانی میکند
     * @param  int  $day  شماره ی روز هفته
     * @return string
     */
    public function numberToWeekday(int $day)
    {
        switch ($day) {
            case 1:
                return 'شنبه';
                break;
            case 2:
                return 'یکشنبه';
                break;
            case 3:
                return 'دوشنبه';
                break;
            case 4:
                return 'سه شنبه';
                break;
            case 5:
                return 'چهار شنبه';
                break;
            case 6:
                return 'پنج شنبه';
                break;
            case 7:
                return 'جمعه';
                break;
        }
        return '';
    }

    /**
     * تاریخ ثبت  را به صورت شمسی بازگردانی میکند
     * یا اگر تاریخ خیلی نزدیک به امروز است، آن را به صورت فارسی و ساده بیان می نماید
     * @param  boolean  $isText  اگر این متغیر صحیح باشد، زمان را به صورت دیروز، هفته ی پیش و ... نمایش میدهد و
     *                           اگر غلط باشد، زمان را به صورت تاریخ شمسی نشان میدهد. this function just will
     *                           work in object class and must exist "created_at" attribute in $this->model
     *                           that is  main must exist
     *                           $this->>model->created_at attribute for work this function with that
     * @param  string   $date    تاریخ ورودی
     * @return string
     */
    public function descriptive_date($date)
    {

        $today = date("Y-m-d");
        $diff = date_diff(date_create($date), date_create($today));
        $diffH = (int) $diff->format('%h');
        $diffD = (int) $diff->format('%d');
        $diffM = (int) $diff->format('%m');
        $diffY = (int) $diff->format('%y');


        if ($diffY == 0) {
            switch ($diffM) {
                case 0:
                    if ($diffD == 0) {
                        switch ($diffH) {
                            case 0:
                                return 'دقایقی قبل';
                            case 1:
                                return 'یک ساعت قبل';
                            case 2:
                                return 'دو ساعت قبل';
                            case 3:
                                return 'سه ساعت قبل';
                            case 4:
                                return 'چهار ساعت قبل';
                            default:
                                return 'ساعاتی قبل';
                        }
                    }
                    if ($diffD == 1) {
                        return 'دیروز';
                    }
                    if ($diffD == 2) {
                        return 'پریروز';
                    }
                    if (($diffD > 2 && $diffD < 7)) {
                        $day = '';
                        switch ($diffD) {
                            case 3:
                                $day = 'سه';
                                break;
                            case 4:
                                $day = 'چهار';
                                break;
                            case 5:
                                $day = 'پنج';
                                break;
                            case 6:
                                $day = 'شش';
                        }

                        return $day.' '.'روز قبل';
                    }

                    if ($diffD >= 7 && $diffD < 14) {
                        return 'هفته ی گذشته';
                    }
                    if ($diffD >= 14 && $diffD < 24) {
                        return 'دو هفته ی قبل';
                    }
                    if ($diffD >= 24 && $diffD < 31) {
                        return 'سه هفته قبل';
                    }
                case 1:
                    return 'یک ماه قبل';
                case 2:
                    return 'دو ماه قبل';
                case 3:
                    return 'سه ماه قبل';
                case 4:
                    return 'چهار ماه قبل';
                case 5:
                    return 'پنج ماه قبل';
                case 6:
                    return 'شش ماه قبل';
                case 7:
                    return 'هفت ماه قبل';
                case 8:
                    return 'هشت ماه قبل';
                case 9:
                    return 'نه ماه قبل';
                case 10:
                    return 'ده ماه قبل';
                case 11:
                    return 'یازده ماه قبل';
                case 12:
            }
        }
        switch ($diffY) {
            case 1:
                return 'پارسال';
            case 2:
                return 'دوسال قبل';
        }


        return $this->convert_date($date);

    }

    /**
     * تعداد روز ها را به تعداد ماه و سال و یا روز تبدیل می کند، این تابع صرفا برای نمایش بخش های پلن
     * کاربری برای خرید یک اشتراک به کار می رود
     * @param  integer|string  $days   تعداد روزهایی که باید به متن تبدیل شود
     * @param  boolean         $withH  در صورتی که این پارامتر صحیح باشد، حرف "ه" به آخر کلمات زمان افزوده میشود، مثلا سال به ساله تبدیل و 1 سال به 1 ساله تبدیل میشود
     * @return string
     */
    public function day2Text($days, $withH = true)
    {
        $days = (integer) $days;
        if ($days < 7) {
            return (string) $days.'روز'.($withH ? 'ه ' : ' ');
        }
        if ($days >= 7 && $days < 30) {
            $days = round($days / 7);

            return (string) $days.'هفته'.($withH ? 'ای ' : ' ');
        }
        if ($days >= 30 && $days < 365) {
            $days = round($days / 30);

            return (string) $days.'ماه'.($withH ? 'ه ' : ' ');
        }
        if ($days >= 365) {
            $days = round($days / 365);

            return (string) $days.'سال'.($withH ? 'ه ' : ' ');
        }
    }

    public function differenceHour($startDate, $endDate)
    {
        $date1 = $startDate;
        $date2 = $endDate;

        $diff = abs(strtotime($date2) - strtotime($date1));


//		$years = floor($diff / (365*60*60*24));
//		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//


        return floor($diff / (60 * 60));
    }

    public function differenceDateYear($startDate, $endDate)
    {


        $d1 = new \DateTime($endDate);
        $d2 = new \DateTime($startDate);

        $diff = $d2->diff($d1);

        return $diff->y;


    }

    /**
     * این تابع اختلاف سال بین دو تاریخ را برمیگرداند، ورودی باید به میلادی باشد
     * @param $startDate
     * @param $endDate
     * @return int
     */
    public function differenceDateDay($startDate, $endDate)
    {


        $d1 = new \DateTime($endDate);
        $d2 = new \DateTime($startDate);

        $diff = $d2->diff($d1);
        $year = $diff->y;
        $month = $diff->m;
        $days = $diff->d;

        return $days + ($month * 30) + ($year * 365);
    }

    public function manipulicateDate($now, $text)
    {
        return date(
            'Y-m-d',
            strtotime(
                $now.' '.$text
            )
        );
    }

    /**
     * @param $now  string تاریخ فعلی
     * @param $text string متن تاریخ جدید  مثال : +1 hour or   -1day
     * @return false|string
     */
    public function manipulicateDateTime($now, $text)
    {
        return date(
            'Y-m-d H:i:s',
            strtotime(
                $now.' '.$text
            )
        );
    }

    /**
     * میزان زمانی که گذشته را به صورت متنی بازگردانی میکند
     *
     *
     * مثلا ده دقیقه قبل
     *
     *
     * یک ساعت قبل
     *
     *
     * یک ساعت بعد و ...
     *
     *
     * @param $deadLine
     * @return int|string
     */
    public function timeLeft($deadLine)
    {
        $deadLine = strtotime($deadLine);
        $timeRemaining = $deadLine - $_SERVER['REQUEST_TIME'];
        if ($timeRemaining < 0) {
            $timeRemaining = abs($timeRemaining);
            $end =  'قبل';
        } else if (!$timeRemaining) return 0;
        else $end =  'مانده';
        $timeRemaining = $timeRemaining / (60 * 60 * 24 * 365);    //converted into years
        $yrs = floor($timeRemaining);                        //removed the decimal part if any
        $timeRemaining = (($timeRemaining - $yrs) * 365);         //converted into days
        $days = floor($timeRemaining);                      //removed the decimal part if any
        $timeRemaining = (($timeRemaining - $days) * 24);        //converted into hrs
        $hrs = floor($timeRemaining);                      //removed the decimal part if any
        $timeRemaining = (($timeRemaining - $hrs) * 60);        //converted into mins
        $min = floor($timeRemaining);                     //removed decimals if any
        $timeRemaining = (($timeRemaining - $min) * 60);       //converted into seconds
        $sec = floor($timeRemaining);                    //removed decimals

        if ($yrs) {
            return $yrs . ' ' .  'سال' . ' ' . $end;
        }

        if ($days > 31) {
            return round($days / 30) . ' ' .  'ماه' . ' ' . $end;
        }

        if ($days > 7) {
            return round($days / 7) . ' ' .  'هفته' . ' ' . $end;
        }

        if ($days) {
            return $days . ' ' .  'روز' . ' ' . $end;
        }

        if ($hrs) {
            return $hrs . ' ' .  'ساعت' . ' ' . $end;
        }

        if ($min) {
            return $min . ' ' .  'دقیقه' . ' ' . $end;
        }

        if ($sec) {
            return  'چند لحظه' . ' ' . $end;
        }


    }



}