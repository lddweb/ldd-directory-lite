<?php
/**
 * Trial basis, this file is full of functions at the moment and isn't quite an object yet. In an effort to
 * have this plugin work anywhere in the world, we may need to handle the validation and presentation of phone
 * numbers from within an object for best portability.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


function ldl_sanitize_phone($number) {
    return preg_replace('/[^0-9+]/', '', $number);
}


function ldl_format_phone($phone, $locale = 'US') {

    if ('US' == $locale) {
        $phone = preg_replace('/[^[:digit:]]/', '', $phone);
        if (10 == strlen($phone)) {
            preg_match('/(\d{3})(\d{3})(\d{4})/', $phone, $match);

            return "({$match[1]}) {$match[2]}-{$match[3]}";
        }
    }

    return $phone; // because I lost it
}


/**
 * Credit: http://james.cridland.net/code/format_uk_phonenumbers.html
 */
function ldl_format_uk($number) {


    // Change the international number format and remove any non-number character
    $number = preg_replace('/[^0-9]/', '', str_replace('+', '00', $number));
    $arr = ldl_uk_split($number, explode(',', ldl_get_uk_format($number)));

    // Add brackets around first split of numbers if number starts with 01 or 02
    if (substr($number, 0, 2) == '01' || substr($number, 0, 2) == '02')
        $arr[0] = '(' . $arr[0] . ')';

    // Convert array back into string, split by spaces
    $formatted = implode(' ', $arr);

    return $formatted;
}

function ldl_uk_split($number, $split) {
    $start = 0;
    $array = array();
    foreach ($split as $value) {
        $array[] = substr($number, $start, $value);
        $start = $start + $value;
    }

    return $array;
}

function ldl_get_uk_format($number) {

    // This uses full codes from http://www.area-codes.org.uk/formatting.shtml
    $formats = array(
        '02'      => '3,4,4',
        '03'      => '4,3,4',
        '05'      => '3,4,4',
        '0500'    => '4,6',
        '07'      => '5,6',
        '070'     => '3,4,4',
        '076'     => '3,4,4',
        '07624'   => '5,6',
        '08'      => '4,3,4',
        '09'      => '4,3,4',
        '01'      => '5,6',
        '011'     => '4,3,4',
        '0121'    => '4,3,4',
        '0131'    => '4,3,4',
        '0141'    => '4,3,4',
        '0151'    => '4,3,4',
        '0161'    => '4,3,4',
        '0191'    => '4,3,4',
        '013873'  => '6,5',
        '015242'  => '6,5',
        '015394'  => '6,5',
        '015395'  => '6,5',
        '015396'  => '6,5',
        '016973'  => '6,5',
        '016974'  => '6,5',
        '016977'  => '6,5',
        '0169772' => '6,4',
        '0169773' => '6,4',
        '017683'  => '6,5',
        '017684'  => '6,5',
        '017687'  => '6,5',
        '019467'  => '6,5'
    );

    // uksort, pardon the pun
    uksort($formats, 'ldl_uk_sort_callback');

    foreach ($formats as $k => $v) {
        if (substr($number, 0, strlen($k)) == $k)
            break;
    }

    return $v;
}

function ldl_uk_sort_callback($a, $b) {
    return strlen($b) - strlen($a);
}
