<?php declare(strict_types = 1);

namespace LSB;

/**
 * Convert character string to binary code
 *
 * @param string $str
 * @return string
 */
function toBin ($str): string
{
    $str = (string)$str;
    $l   = strlen($str);
    $result = '';
    while($l--) {
        $result = str_pad(decbin(ord($str[$l])), 8, "0", STR_PAD_LEFT) . $result;
    }
    return $result;
}

/**
 * Convert string with binary code to character string
 *
 * @param string $binary
 * @return string
 */
function toString ($binary): string
{
    $charBits = str_split($binary, 8);
    $string = "";
    foreach ($charBits as $charBit) {
        $string .= pack('H*', base_convert($charBit, 2, 16));
    }
    return $string;
}