<?php
/**
 * Copyright (c) 2018.
 * Author: YiiMan Tm
 * Programmer: gholamreza beheshtian
 * mobile: 09353466620 | +17272282283
 * WebSite:http://yiiman.ir
 *
 *
 */



namespace YiiMan\functions;

use function date;
use DateTime;
use function round;
use function strtotime;


class functions
{


    const PRICE_UNIT_RIAL = 1;
    const PRICE_UNIT_TOMAN = 2;

    public function jdate($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'en')
    {
        $T_sec = 0; /* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

        if ($time_zone != 'local') {
            date_default_timezone_set(($time_zone == '') ? 'Asia/Tehran' : $time_zone);
        }
        $ts = $T_sec + (($timestamp == '' or $timestamp == 'now') ? time() : self::tr_num(
                $timestamp
            ));
        $date = explode('_', date('H_i_j_n_O_P_s_w_Y', $ts));
        list ($j_y, $j_m, $j_d) = self::gregorian_to_jalali($date[8], $date[3], $date[2]);
        $doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
        $kab = ($j_y % 33 % 4 - 1 == (int)($j_y % 33 * .05)) ? 1 : 0;
        $sl = strlen($format);
        $out = '';
        for ($i = 0; $i < $sl; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub == '\\') {
                $out .= substr($format, ++$i, 1);
                continue;
            }
            switch ($sub) {

                case 'E':
                case 'R':
                case 'x':
                case 'X':
                    $out .= 'http://jdf.scr.ir';
                    break;

                case 'B':
                case 'e':
                case 'g':
                case 'G':
                case 'h':
                case 'I':
                case 'T':
                case 'u':
                case 'Z':
                    $out .= date($sub, $ts);
                    break;

                case 'a':
                    $out .= ($date[0] < 12) ? 'ق.ظ' : 'ب.ظ';
                    break;

                case 'A':
                    $out .= ($date[0] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;

                case 'b':
                    $out .= (int)($j_m / 3.1) + 1;
                    break;

                case 'c':
                    $out .= $j_y . '/' . $j_m . '/' . $j_d . ' ،' . $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[5];
                    break;

                case 'C':
                    $out .= (int)(($j_y + 99) / 100);
                    break;

                case 'd':
                    $out .= ($j_d < 10) ? '0' . $j_d : $j_d;
                    break;

                case 'D':
                    $out .= self::jdate_words(
                        [
                            'kh' => $date[7],
                        ],
                        ' '
                    );
                    break;

                case 'f':
                    $out .= self::jdate_words(
                        [
                            'ff' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'F':
                    $out .= self::jdate_words(
                        [
                            'mm' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'H':
                    $out .= $date[0];
                    break;

                case 'i':
                    $out .= $date[1];
                    break;

                case 'j':
                    $out .= $j_d;
                    break;

                case 'J':
                    $out .= self::jdate_words(
                        [
                            'rr' => $j_d,
                        ],
                        ' '
                    );
                    break;

                case 'k':
                    $out .= self::tr_num(
                        100 - (int)($doy / ($kab + 365) * 1000) / 10,
                        $tr_num
                    );
                    break;

                case 'K':
                    $out .= self::tr_num(
                        (int)($doy / ($kab + 365) * 1000) / 10,
                        $tr_num
                    );
                    break;

                case 'l':
                    $out .= self::jdate_words(
                        [
                            'rh' => $date[7],
                        ],
                        ' '
                    );
                    break;

                case 'L':
                    $out .= $kab;
                    break;

                case 'm':
                    $out .= ($j_m > 9) ? $j_m : '0' . $j_m;
                    break;

                case 'M':
                    $out .= self::jdate_words(
                        [
                            'km' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'n':
                    $out .= $j_m;
                    break;

                case 'N':
                    $out .= $date[7] + 1;
                    break;

                case 'o':
                    $jdw = ($date[7] == 6) ? 0 : $date[7] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
                    break;

                case 'O':
                    $out .= $date[4];
                    break;

                case 'p':
                    $out .= self::jdate_words(
                        [
                            'mb' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'P':
                    $out .= $date[5];
                    break;

                case 'q':
                    $out .= self::jdate_words(
                        [
                            'sh' => $j_y,
                        ],
                        ' '
                    );
                    break;

                case 'Q':
                    $out .= $kab + 364 - $doy;
                    break;

                case 'r':
                    $key = self::jdate_words(
                        [
                            'rh' => $date[7],
                            'mm' => $j_m,
                        ]
                    );
                    $out .= $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[4] . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
                    break;

                case 's':
                    $out .= $date[6];
                    break;

                case 'S':
                    $out .= 'ام';
                    break;

                case 't':
                    $out .= ($j_m != 12) ? (31 - (int)($j_m / 6.5)) : ($kab + 29);
                    break;

                case 'U':
                    $out .= $ts;
                    break;

                case 'v':
                    $out .= self::jdate_words(
                        [
                            'ss' => substr($j_y, 2, 2),
                        ],
                        ' '
                    );
                    break;

                case 'V':
                    $out .= self::jdate_words(
                        [
                            'ss' => $j_y,
                        ],
                        ' '
                    );
                    break;

                case 'w':
                    $out .= ($date[7] == 6) ? 0 : $date[7] + 1;
                    break;

                case 'W':
                    $avs = (($date[7] == 6) ? 0 : $date[7] + 1) - ($doy % 7);
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int)(($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    } else if ($num < 1) {
                        $num = ($avs == 4 or $avs == (($j_y % 33 % 4 - 2 == (int)($j_y % 33 * .05)) ? 5 : 4)) ? 53 : 52;
                    }
                    $aks = $avs + $kab;
                    if ($aks == 7) {
                        $aks = 0;
                    }
                    $out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                    break;

                case 'y':
                    $out .= substr($j_y, 2, 2);
                    break;

                case 'Y':
                    $out .= $j_y;
                    break;

                case 'z':
                    $out .= $doy;
                    break;

                default:
                    $out .= $sub;
            }
        }

        return ($tr_num != 'en') ? self::tr_num($out, 'fa', '.') : $out;
    }

    public function jstrftime($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa')
    {
        $T_sec = 0; /* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */

        if ($time_zone != 'local') {
            date_default_timezone_set(($time_zone == '') ? 'Asia/Tehran' : $time_zone);
        }
        $ts = $T_sec + (($timestamp == '' or $timestamp == 'now') ? time() : self::tr_num(
                $timestamp
            ));
        $date = explode('_', date('h_H_i_j_n_s_w_Y', $ts));
        list ($j_y, $j_m, $j_d) = self::gregorian_to_jalali($date[7], $date[4], $date[3]);
        $doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
        $kab = ($j_y % 33 % 4 - 1 == (int)($j_y % 33 * .05)) ? 1 : 0;
        $sl = strlen($format);
        $out = '';
        for ($i = 0; $i < $sl; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub == '%') {
                $sub = substr($format, ++$i, 1);
            } else {
                $out .= $sub;
                continue;
            }
            switch ($sub) {

                /* Day */
                case 'a':
                    $out .= self::jdate_words(
                        [
                            'kh' => $date[6],
                        ],
                        ' '
                    );
                    break;

                case 'A':
                    $out .= self::jdate_words(
                        [
                            'rh' => $date[6],
                        ],
                        ' '
                    );
                    break;

                case 'd':
                    $out .= ($j_d < 10) ? '0' . $j_d : $j_d;
                    break;

                case 'e':
                    $out .= ($j_d < 10) ? ' ' . $j_d : $j_d;
                    break;

                case 'j':
                    $out .= str_pad($doy + 1, 3, 0, STR_PAD_LEFT);
                    break;

                case 'u':
                    $out .= $date[6] + 1;
                    break;

                case 'w':
                    $out .= ($date[6] == 6) ? 0 : $date[6] + 1;
                    break;

                /* Week */
                case 'U':
                    $avs = (($date[6] < 5) ? $date[6] + 2 : $date[6] - 5) - ($doy % 7);
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int)(($doy + $avs) / 7) + 1;
                    if ($avs > 3 or $avs == 1) {
                        $num--;
                    }
                    $out .= ($num < 10) ? '0' . $num : $num;
                    break;

                case 'V':
                    $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int)(($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    } else if ($num < 1) {
                        $num = ($avs == 4 or $avs == (($j_y % 33 % 4 - 2 == (int)($j_y % 33 * .05)) ? 5 : 4)) ? 53 : 52;
                    }
                    $aks = $avs + $kab;
                    if ($aks == 7) {
                        $aks = 0;
                    }
                    $out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                    break;

                case 'W':
                    $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                    if ($avs < 0) {
                        $avs += 7;
                    }
                    $num = (int)(($doy + $avs) / 7) + 1;
                    if ($avs > 3) {
                        $num--;
                    }
                    $out .= ($num < 10) ? '0' . $num : $num;
                    break;

                /* Month */
                case 'b':
                case 'h':
                    $out .= self::jdate_words(
                        [
                            'km' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'B':
                    $out .= self::jdate_words(
                        [
                            'mm' => $j_m,
                        ],
                        ' '
                    );
                    break;

                case 'm':
                    $out .= ($j_m > 9) ? $j_m : '0' . $j_m;
                    break;

                /* Year */
                case 'C':
                    $out .= substr($j_y, 0, 2);
                    break;

                case 'g':
                    $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= substr(
                        ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y),
                        2,
                        2
                    );
                    break;

                case 'G':
                    $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                    $dny = 364 + $kab - $doy;
                    $out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
                    break;

                case 'y':
                    $out .= substr($j_y, 2, 2);
                    break;

                case 'Y':
                    $out .= $j_y;
                    break;

                /* Time */
                case 'H':
                    $out .= $date[1];
                    break;

                case 'I':
                    $out .= $date[0];
                    break;

                case 'l':
                    $out .= ($date[0] > 9) ? $date[0] : ' ' . (int)$date[0];
                    break;

                case 'M':
                    $out .= $date[2];
                    break;

                case 'p':
                    $out .= ($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;

                case 'P':
                    $out .= ($date[1] < 12) ? 'ق.ظ' : 'ب.ظ';
                    break;

                case 'r':
                    $out .= $date[0] . ':' . $date[2] . ':' . $date[5] . ' ' . (($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر');
                    break;

                case 'R':
                    $out .= $date[1] . ':' . $date[2];
                    break;

                case 'S':
                    $out .= $date[5];
                    break;

                case 'T':
                    $out .= $date[1] . ':' . $date[2] . ':' . $date[5];
                    break;

                case 'X':
                    $out .= $date[0] . ':' . $date[2] . ':' . $date[5];
                    break;

                case 'z':
                    $out .= date('O', $ts);
                    break;

                case 'Z':
                    $out .= date('T', $ts);
                    break;

                /* Time and Date Stamps */
                case 'c':
                    $key = self::jdate_words(
                        [
                            'rh' => $date[6],
                            'mm' => $j_m,
                        ]
                    );
                    $out .= $date[1] . ':' . $date[2] . ':' . $date[5] . ' ' . date(
                            'P',
                            $ts
                        ) . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
                    break;

                case 'D':
                    $out .= substr(
                            $j_y,
                            2,
                            2
                        ) . '/' . (($j_m > 9) ? $j_m : '0' . $j_m) . '/' . (($j_d < 10) ? '0' . $j_d : $j_d);
                    break;

                case 'F':
                    $out .= $j_y . '-' . (($j_m > 9) ? $j_m : '0' . $j_m) . '-' . (($j_d < 10) ? '0' . $j_d : $j_d);
                    break;

                case 's':
                    $out .= $ts;
                    break;

                case 'x':
                    $out .= substr(
                            $j_y,
                            2,
                            2
                        ) . '/' . (($j_m > 9) ? $j_m : '0' . $j_m) . '/' . (($j_d < 10) ? '0' . $j_d : $j_d);
                    break;

                /* Miscellaneous */
                case 'n':
                    $out .= "\n";
                    break;

                case 't':
                    $out .= "\t";
                    break;

                case '%':
                    $out .= '%';
                    break;

                default:
                    $out .= $sub;
            }
        }

        return ($tr_num != 'en') ? self::tr_num($out, 'fa', '.') : $out;
    }

    public function jmktime($h = '', $m = '', $s = '', $jm = '', $jd = '', $jy = '', $is_dst = -1)
    {
        $h = self::tr_num($h);
        $m = self::tr_num($m);
        $s = self::tr_num($s);
        $jm = self::tr_num($jm);
        $jd = self::tr_num($jd);
        $jy = self::tr_num($jy);
        if ($h == '' and $m == '' and $s == '' and $jm == '' and $jd == '' and $jy == '') {
            return mktime();
        } else {
            list ($year, $month, $day) = self::jalali_to_gregorian($jy, $jm, $jd);

            return mktime($h, $m, $s, $month, $day, $year, $is_dst);
        }
    }

    public function jgetdate($timestamp = '', $none = '', $tz = 'Asia/Tehran', $tn = 'en')
    {
        $ts = ($timestamp == '') ? time() : self::tr_num($timestamp);
        $jdate = explode('_', self::jdate('F_G_i_j_l_n_s_w_Y_z', $ts, '', $tz, $tn));

        return [
            'seconds' => self::tr_num((int)self::tr_num($jdate[6]), $tn),
            'minutes' => self::tr_num((int)self::tr_num($jdate[2]), $tn),
            'hours' => $jdate[1],
            'mday' => $jdate[3],
            'wday' => $jdate[7],
            'mon' => $jdate[5],
            'year' => $jdate[8],
            'yday' => $jdate[9],
            'weekday' => $jdate[4],
            'month' => $jdate[0],
            0 => self::tr_num($ts, $tn),
        ];
    }

    public function jcheckdate($jm, $jd, $jy)
    {
        $jm = self::tr_num($jm);
        $jd = self::tr_num($jd);
        $jy = self::tr_num($jy);
        $l_d = ($jm == 12) ? (($jy % 33 % 4 - 1 == (int)($jy % 33 * .05)) ? 30 : 29) : 31 - (int)($jm / 6.5);

        return ($jm > 0 and $jd > 0 and $jy > 0 and $jm < 13 and $jd <= $l_d) ? true : false;
    }

    public function tr_num($str, $mod = 'en', $mf = '٫')
    {
        $num_a = [
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '.',
        ];
        $key_a = [
            '۰',
            '۱',
            '۲',
            '۳',
            '۴',
            '۵',
            '۶',
            '۷',
            '۸',
            '۹',
            $mf,
        ];

        return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace(
            $key_a,
            $num_a,
            $str
        );
    }

    public function jdate_words($array, $mod = '')
    {
        foreach ($array as $type => $num) {
            $num = (int)self::tr_num($num);
            switch ($type) {

                case 'ss':
                    $sl = strlen($num);
                    $xy3 = substr($num, 2 - $sl, 1);
                    $h3 = $h34 = $h4 = '';
                    if ($xy3 == 1) {
                        $p34 = '';
                        $k34 = [
                            'ده',
                            'یازده',
                            'دوازده',
                            'سیزده',
                            'چهارده',
                            'پانزده',
                            'شانزده',
                            'هفده',
                            'هجده',
                            'نوزده',
                        ];
                        $h34 = $k34[substr($num, 2 - $sl, 2) - 10];
                    } else {
                        $xy4 = substr($num, 3 - $sl, 1);
                        $p34 = ($xy3 == 0 or $xy4 == 0) ? '' : ' و ';
                        $k3 = [
                            '',
                            '',
                            'بیست',
                            'سی',
                            'چهل',
                            'پنجاه',
                            'شصت',
                            'هفتاد',
                            'هشتاد',
                            'نود',
                        ];
                        $h3 = $k3[$xy3];
                        $k4 = [
                            '',
                            'یک',
                            'دو',
                            'سه',
                            'چهار',
                            'پنج',
                            'شش',
                            'هفت',
                            'هشت',
                            'نه',
                        ];
                        $h4 = $k4[$xy4];
                    }
                    $array[$type] = (($num > 99) ? str_ireplace(
                                [
                                    '12',
                                    '13',
                                    '14',
                                    '19',
                                    '20',
                                ],
                                [
                                    'هزار و دویست',
                                    'هزار و سیصد',
                                    'هزار و چهارصد',
                                    'هزار و نهصد',
                                    'دوهزار',
                                ],
                                substr($num, 0, 2)
                            ) . ((substr(
                                    $num,
                                    2,
                                    2
                                ) == '00') ? '' : ' و ') : '') . $h3 . $p34 . $h34 . $h4;
                    break;

                case 'mm':
                    $key = [
                        'فروردین',
                        'اردیبهشت',
                        'خرداد',
                        'تیر',
                        'مرداد',
                        'شهریور',
                        'مهر',
                        'آبان',
                        'آذر',
                        'دی',
                        'بهمن',
                        'اسفند',
                    ];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rr':
                    $key = [
                        'یک',
                        'دو',
                        'سه',
                        'چهار',
                        'پنج',
                        'شش',
                        'هفت',
                        'هشت',
                        'نه',
                        'ده',
                        'یازده',
                        'دوازده',
                        'سیزده',
                        'چهارده',
                        'پانزده',
                        'شانزده',
                        'هفده',
                        'هجده',
                        'نوزده',
                        'بیست',
                        'بیست و یک',
                        'بیست و دو',
                        'بیست و سه',
                        'بیست و چهار',
                        'بیست و پنج',
                        'بیست و شش',
                        'بیست و هفت',
                        'بیست و هشت',
                        'بیست و نه',
                        'سی',
                        'سی و یک',
                    ];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'rh':
                    $key = [
                        'یکشنبه',
                        'دوشنبه',
                        'سه شنبه',
                        'چهارشنبه',
                        'پنجشنبه',
                        'جمعه',
                        'شنبه',
                    ];
                    $array[$type] = $key[$num];
                    break;

                case 'sh':
                    $key = [
                        'مار',
                        'اسب',
                        'گوسفند',
                        'میمون',
                        'مرغ',
                        'سگ',
                        'خوک',
                        'موش',
                        'گاو',
                        'پلنگ',
                        'خرگوش',
                        'نهنگ',
                    ];
                    $array[$type] = $key[$num % 12];
                    break;

                case 'mb':
                    $key = [
                        'حمل',
                        'ثور',
                        'جوزا',
                        'سرطان',
                        'اسد',
                        'سنبله',
                        'میزان',
                        'عقرب',
                        'قوس',
                        'جدی',
                        'دلو',
                        'حوت',
                    ];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'ff':
                    $key = [
                        'بهار',
                        'تابستان',
                        'پاییز',
                        'زمستان',
                    ];
                    $array[$type] = $key[(int)($num / 3.1)];
                    break;

                case 'km':
                    $key = [
                        'فر',
                        'ار',
                        'خر',
                        'تی‍',
                        'مر',
                        'شه‍',
                        'مه‍',
                        'آب‍',
                        'آذ',
                        'دی',
                        'به‍',
                        'اس‍',
                    ];
                    $array[$type] = $key[$num - 1];
                    break;

                case 'kh':
                    $key = [
                        'ی',
                        'د',
                        'س',
                        'چ',
                        'پ',
                        'ج',
                        'ش',
                    ];
                    $array[$type] = $key[$num];
                    break;

                default:
                    $array[$type] = $num;
            }
        }

        return ($mod == '') ? $array : implode($mod, $array);
    }

    public function gregorian_to_jalali($g_y, $g_m, $g_d, $mod = '')
    {
        $g_y = self::tr_num($g_y);
        $g_m = self::tr_num($g_m);
        $g_d = self::tr_num($g_d); /* <= :اين سطر ، جزء تابع اصلي نيست */
        $d_4 = $g_y % 4;
        $g_a = [
            0,
            0,
            31,
            59,
            90,
            120,
            151,
            181,
            212,
            243,
            273,
            304,
            334,
        ];
        $doy_g = $g_a[(int)$g_m] + $g_d;
        if ($d_4 == 0 and $g_m > 2) {
            $doy_g++;
        }
        $d_33 = (int)((($g_y - 16) % 132) * .0305);
        $a = ($d_33 == 3 or $d_33 < ($d_4 - 1) or $d_4 == 0) ? 286 : 287;
        $b = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);
        if ((int)(($g_y - 10) / 63) == 30) {
            $a--;
            $b++;
        }
        if ($doy_g > $b) {
            $jy = $g_y - 621;
            $doy_j = $doy_g - $b;
        } else {
            $jy = $g_y - 622;
            $doy_j = $doy_g + $a;
        }
        if ($doy_j < 187) {
            $jm = (int)(($doy_j - 1) / 31);
            $jd = $doy_j - (31 * $jm++);
        } else {
            $jm = (int)(($doy_j - 187) / 30);
            $jd = $doy_j - 186 - ($jm * 30);
            $jm += 7;
        }

        return ($mod == '') ? [
            $jy,
            $jm,
            $jd,
        ] : $jy . $mod . $jm . $mod . $jd;
    }

    public function jalali_to_gregorian($j_y, $j_m, $j_d, $mod = '')
    {
        $j_y = self::tr_num($j_y);
        $j_m = self::tr_num($j_m);
        $j_d = self::tr_num($j_d); /* <= :اين سطر ، جزء تابع اصلي نيست */
        $d_4 = ($j_y + 1) % 4;
        $doy_j = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d : (($j_m - 7) * 30) + $j_d + 186;
        $d_33 = (int)((($j_y - 55) % 132) * .0305);
        $a = ($d_33 != 3 and $d_4 <= $d_33) ? 287 : 286;
        $b = (($d_33 == 1 or $d_33 == 2) and ($d_33 == $d_4 or $d_4 == 1)) ? 78 : (($d_33 == 3 and $d_4 == 0) ? 80 : 79);
        if ((int)(($j_y - 19) / 63) == 20) {
            $a--;
            $b++;
        }
        if ($doy_j <= $a) {
            $gy = $j_y + 621;
            $gd = $doy_j + $b;
        } else {
            $gy = $j_y + 622;
            $gd = $doy_j - $a;
        }
        foreach (
            [
                0,
                31,
                ($gy % 4 == 0) ? 29 : 28,
                31,
                30,
                31,
                30,
                31,
                31,
                30,
                31,
                30,
                31,
            ] as $gm => $v
        ) {
            if ($gd <= $v) {
                break;
            }
            $gd -= $v;
        }

        return ($mod == '') ? [
            $gy,
            $gm,
            $gd,
        ] : $gy . $mod . $gm . $mod . $gd;
    }

    /**
     * Will Change yyyy-mm-dd hh:ii:ss from gregorian to shmsi(jalali)
     *
     * @param $in_datetime
     *
     * @return string will return string date and if DateTime was =='0000-00-00 00:00:00' will return '---'
     */
    public function convertdatetime($in_datetime, $delimiter = null)
    {
        if ($in_datetime && $in_datetime != '0000-00-00 00:00:00') {

            if ($delimiter) {
                $datetime = explode($delimiter, $in_datetime);
                echo '<pre>';
                var_dump($datetime);
                die();

            } else {
                $datetime = explode(' ', $in_datetime);

            }

            return self::jdate('Y/m/d', strtotime($datetime[0])) . ' - ' . $datetime[1];
        }

        return '---';
    }

    /**
     * check your shansi date and clear 00:00:00 text from it and return :
     *
     * yy:mm:dd as string
     */
    public function DateTime_Clear($date)
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
            self::gregorian_to_jalali(
                $datetime[0],
                $datetime[1],
                explode(' ', $datetime[2])[0],
                '/'
            ) .
            ' ' . explode(' ', $datetime[2])[1]
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
     * created by amintado
     * will convert your gregorian date to  shamsi and then clear 00:00:00 text from it and retyrn shamsi
     * YYYY:MM:DD
     */
    public function Date_To_Shamsi($date)
    {
        try {
            return self::DateTime_Clear($date . ' 00:00:00');
        } catch (\Exception $e) {
            return 'تعریف نشده';
        }
    }

    /**
     * created by amintado
     * will convert your date to  gregorian
     */
    public function Date_to_Gregory($date)
    {
        if (count(explode('/', $date)) < 3) {
            if (count(explode('-', $date)) < 3) {
                return '';
            } else {
                return trim(
                    self::jalali_to_gregorian(
                        explode('-', $date)[0],
                        explode('-', $date)[1],
                        explode('-', $date)[2],
                        '-'
                    )
                );
            }
        }

        return trim(
            self::jalali_to_gregorian(
                explode('/', $date)[0],
                explode('/', $date)[1],
                explode('/', $date)[2],
                '-'
            ) . ' 00:00:00'
        );
    }

    /**
     * @param     $in_date
     * @param int $type
     *
     * @return mixed|null|string
     */
    public function convertdate($in_date, $type = 0, $delimiter = '/')
    {
        if ($type === 0) {
            if ($in_date) {
                if (strlen($in_date) > 10) {
                    $datetime = explode(' ', $in_date);
                    $in_date = $datetime[0];
                }
                if ($in_date == '0000-00-00') {
                    return null;
                }

                return self::jdate('Y/m/d', strtotime($in_date));
            }

            return null;
        } else if ($type === 1) {
            if ($in_date && $in_date != '0000-00-00') {
                if (strlen($in_date) > 10) {
                    $datetime = explode(' ', $in_date);
                    $in_date = $datetime[0];
                }
                $jdate = explode($delimiter, $in_date);
                if (count($jdate)) {

                    return implode(
                        '-',
                        self::jalali_to_gregorian(
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

    /**
     *
     * @param $years string example input 2017-2018
     *
     * @return string
     */
    public function yearsToShamsi($years)
    {
        $years = explode('-', $years);
        $years[0] = (string)(substr(functions::convertdate($years[0] . '-10-12'), 0, 4));
        $years[1] = substr(functions::convertdate($years[1] . '-10-12'), 0, 4);

        return $years[0] . '-' . $years[1];
    }

    public function mounthToShamsi($years)
    {
        $years = explode('-', $years);
        $years[0] = (string)(substr(functions::convertdate($years[0] . '-10-12'), 0, 4));
        $years[1] = substr(functions::convertdate($years[1] . '-10-12'), 0, 4);

        return $years[0] . '-' . $years[1];
    }

    /**
     *
     * @param $year string example 2017
     *
     * @return string
     */
    public function YearToShamsi($year)
    {
        $year = $year . '-01-01';
        $year = substr(functions::convertdate($year), 0, 4);

        return $year;
    }

    /**
     * این تابع متن واحد پولی را گرفته سپس مبلغ را طبق تنظیمات سایت رند میکند و واحد پولی را به متن
     * میپسباند و یک متن واحد که شامله عدد و واحد پولی میباشد را بر میگرداند
     *
     * @param $price
     * @param $unit
     *
     * @return string یک هزار تومان
     */
    public function priceText($price, $unit)
    {

        if (empty($price)){
            return  '0';
        }
        /* < Round Type > */
        {
            switch (Yii::$app->Options->roundPrice) {
                case 'up':
                    $roundMode = PHP_ROUND_HALF_UP;
                    break;
                case 'down':
                    $roundMode = PHP_ROUND_HALF_DOWN;
                    break;
                default:
                    $roundMode = PHP_ROUND_HALF_DOWN;
            }
        }
        /* </ Round Type > */

        /* < Calculate Number > */
        {
            $text = '';
            $price = round((float)$price, 0, $roundMode);


            $length = strlen((string)$price);

            /* < under thousant > */
            {
                if (4 > $length) {
                    $text = $price;
                    if (strpos((string)$price, '.') > 0) {
                        return $text . ' ' . $unit;
                    }
                }
            }
            /* </ under thousent > */

            /* < Million > */
            {
                if (3 < $length && $length < 7) {
                    $text = $price / 1000;
                    if (strpos((string)$price, '.') > 0) {
                        return $text . ' ' . $unit;
                    }
                    $text .= ' هزار';
                }
            }
            /* </ Million > */

            /* < Million > */
            {
                if (6 < $length && $length < 10) {
                    $text = $price / 1000000;
                    if (strpos((string)$price, '.') > 0) {
                        return $text . ' ' . $unit;
                    }
                    $text .= ' میلیون';
                }
            }
            /* </ Million > */

            /* < Milliard > */
            {
                if (9 < $length && $length < 13) {
                    $text = $price / 1000000000;
                    if (strpos((string)$price, '.') > 0) {
                        return $text . ' ' . $unit;
                    }
                    $text .= ' میلیارد';
                }
            }
            /* </ Milliard > */

        }
        /* </ Calculate Number > */

        return $text . ' ' . $unit;

    }

    public function YearToGregorian($year)
    {
        $year = $year . '-10-12';
        $year = substr(functions::Date_to_Gregory($year), 0, 4);

        return $year;
    }

    /**
     * این تابع اعداد داخل یک رشته متنی را از فارسی به لاتین یا از لاتین به فارسی تبدیل میکند
     * @param        $string
     * @param string $type toPersian | toLatin
     *
     * @return mixed
     */
    public function convertDigit($string, $type = 'toPersian')
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        switch ($type) {
            case 'toPersian':
                $num = range(0, 9);
                $out = str_replace($num, $persian, $string);
//					$out   = str_replace( $arabic , $num , $convertedPersianNums );
                break;
            case 'toLatin':
                $num = range(0, 9);
                $convertedPersianNums = str_replace($persian, $num, $string);
                $out = str_replace($arabic, $num, $convertedPersianNums);
                break;

        }


        return $out;
    }

    /**
     * نام روز هفته را با دریافت شماره ی آن بازگردانی میکند
     * @param int $day شماره ی روز هفته
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
     *
     * @param boolean $isText اگر این متغیر صحیح باشد، زمان را به صورت دیروز، هفته ی پیش و ... نمایش میدهد و
     *                        اگر غلط باشد، زمان را به صورت تاریخ شمسی نشان میدهد. this function just will
     *                        work in object class and must exist "created_at" attribute in $this->model
     *                        that is  main must exist
     *                        $this->>model->created_at attribute for work this function with that
     * @param string $date تاریخ ورودی
     *
     * @return string
     */
    public function shamsiDate($date, $isText)
    {
        if ($isText) {
            $today = date("Y-m-d");
            $diff = date_diff(date_create($date), date_create($today));
            $diff = $diff->format('%d');
            if ($diff == 0) {
                return 'امروز';
            }
            if ($diff == 1) {
                return 'دیروز';
            }
            if ($diff == 2) {
                return 'پریروز';
            }
            if ($diff > 2 && $diff < 7) {
                $day = '';
                switch ($diff) {
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

                return $day . ' ' . 'روز قبل';
            }

            if ($diff > 7 && $diff < 14) {
                return 'هفته ی گذشته';
            }
            if ($diff > 14 && $diff < 24) {
                return 'دو هفته ی قبل';
            }
            if ($diff > 24 && $diff < 31) {
                return 'سه هفته قبل';
            }
            if ($diff > 31 && $diff < 60) {
                return 'یک ماه قبل';
            }

            return $this->convertdate($date);
        } else {
            return $this->convertdate($date);
        }

    }

    /**
     * تعداد روز ها را به تعداد ماه و سال و یا روز تبدیل می کند، این تابع صرفا برای نمایش بخش های پلن
     * کاربری برای خرید یک اشتراک به کار می رود
     *
     * @param integer|string $days تعداد روزهایی که باید به متن تبدیل شود
     * @param boolean $withH در صورتی که این پارامتر صحیح باشد، حرف "ه" به آخر کلمات زمان افزوده میشود، مثلا سال به ساله تبدیل و 1 سال به 1 ساله تبدیل میشود
     * @return string
     */
    public function dayToText($days, $withH = true)
    {
        $days = (integer)$days;
        if ($days < 7) {
            return (string)$days . 'روز' . ($withH ? 'ه ' : ' ');
        }
        if ($days >= 7 && $days < 30) {
            $days = round($days / 7);

            return (string)$days . 'هفته' . ($withH ? 'ای ' : ' ');
        }
        if ($days >= 30 && $days < 365) {
            $days = round($days / 30);

            return (string)$days . 'ماه' . ($withH ? 'ه ' : ' ');
        }
        if ($days >= 365) {
            $days = round($days / 365);

            return (string)$days . 'سال' . ($withH ? 'ه ' : ' ');
        }
    }

    /**
     * این تابع اختلاف دو تاریخ میلادی را به واحد روز بازگردانی می کند
     *
     * @param string $startDate
     * @param string $endDate
     *
     * @return integer تعداد روزهایی که بین دو تاریخ وجود دارد را بر می گرداند
     */
    public function differenceDate($startDate, $endDate)
    {
        $date1 = $startDate;
        $date2 = $endDate;

        $diff = abs(strtotime($date2) - strtotime($date1));


//		$years = floor($diff / (365*60*60*24));
//		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//


        return floor($diff / (60 * 60 * 24));
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

    /**
     * این تابع اختلاف سال بین دو تاریخ را برمیگرداند، ورودی باید به میلادی باشد
     *
     * @param $startDate
     * @param $endDate
     *
     * @return int
     */
    public function differenceDateYear($startDate, $endDate)
    {


        $d1 = new DateTime($endDate);
        $d2 = new DateTime($startDate);

        $diff = $d2->diff($d1);

        return $diff->y;


    }

    /**
     * این تابع اختلاف سال بین دو تاریخ را برمیگرداند، ورودی باید به میلادی باشد
     *
     * @param $startDate
     * @param $endDate
     *
     * @return int
     */
    public function differenceDateDay($startDate, $endDate)
    {


        $d1 = new DateTime($endDate);
        $d2 = new DateTime($startDate);

        $diff = $d2->diff($d1);
        $year=$diff->y;
        $month=$diff->m;
        $days=$diff->d;

        return $days+($month*30)+($year*365);
    }



    /**
     * @param $now  string تاریخ فعلی
     * @param $text string متن تاریخ جدید  مثال : +1 hour or   -1day
     *
     * @return false|string
     */
    public function manipulicateDate($now, $text)
    {
        return date(
            'Y-m-d',
            strtotime(
                $now . ' ' . $text
            )
        );
    }

    /**
     * @param $now  string تاریخ فعلی
     * @param $text string متن تاریخ جدید  مثال : +1 hour or   -1day
     *
     * @return false|string
     */
    public function manipulicateDateTime($now, $text)
    {
        return date(
            'Y-m-d H:i:s',
            strtotime(
                $now . ' ' . $text
            )
        );
    }

    public function limitText($text, $charCount = 500)
    {
        if (empty($text)) {
            return '';
        }
        $string = strip_tags($text);
        if (strlen($string) > $charCount) {

            // truncate string
            $stringCut = substr($string, 0, $charCount);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= '...';
        }
        return $string;
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

    /**
     * return user client ip
     *
     * @return mixed|string
     */
    public function getClientIP()
    {
        $ipaddress = 'UNKNOWN';
        $keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($keys as $k) {
            if (isset($_SERVER[$k]) && !empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP)) {
                $ipaddress = $_SERVER[$k];
                break;
            }
        }
        return $ipaddress;
    }

    /**
     * با توجه به زبان سیستم برای کاربر تاریخ های میلادی را تبدیل میکند
     * @param $datetime
     * @param $calendar string jalali|gregory|arabic
     * @return string
     */
    public function showDateTime($datetime,$calendar='jalali')
    {
        switch ($calendar) {
            case 'jalali':
                return $this->convertdatetime($datetime);
                break;
            case 'gregory':
                return $datetime;
            case 'arabic':
                return $datetime;

        }
    }




    /**
     *  مقدار درصد درخواستی از یک عدد را محاسبه میکند
     * @param $total
     * @param $percent
     * @return float
     */
    public function percent2NumberCalculator($total, $percent)
    {
        return ($percent / 100) * $total;
    }

    /**
     * درصد تغیر بین دو عدد را بازگردانی میکند
     * @param $oldFigure
     * @param $newFigure
     * @return float
     */
    public function number2percentCalculator($oldFigure, $newFigure)
    {
        $percentChange = (($oldFigure - $newFigure) / $oldFigure) * 100;
        return round(abs($percentChange));
    }

    /**
     * custome eval function than can throw exceptions
     * @param $code
     * @return mixed
     */
    function eval($code) {
        $return=eval($code);
        $exception=new class extends \Exception {
            protected $message;
            /** The error code */
            protected $code;
            /** The filename where the error happened  */
            protected $file;
            /** The line where the error happened */
            protected $line;


            public function setLine($line){
                $this->line=$line;
            }

            public function setFile($file){
                $this->file=$file;
            }

            public function __construct($message = null, $code = 0, \Exception $previous = null)
            {
                parent::__construct(500, $message, $code, $previous);
            }
        };

        if ( $return === false && ( $error = error_get_last() ) ) {
            $exception= new $exception($error['message']);
            $exception->setFile($error['file']);
            $exception->setFile($error['line']);
            throw new $exception;
        }




        //$tmp = tmpfile ();
        //$tmpf = stream_get_meta_data ( $tmp );
        //$tmpf = $tmpf ['uri'];
        //fwrite ( $tmp, '<?php ' );
        //fwrite ( $tmp, $code );
        /*fwrite ( $tmp, '  ?>' );*/
        //$ret = require $tmpf;
        //fclose ( $tmp );
        //return $ret;
    }
}
