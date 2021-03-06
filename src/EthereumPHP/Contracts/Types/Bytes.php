<?php

/**
 * This file is part of web3.php package.
 * 
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace EthereumPHP\Contracts\Types;

use InvalidArgumentException;
use EthereumPHP\Utils;
use EthereumPHP\Contracts\SolidityType;
use EthereumPHP\Contracts\Types\IType;

class Bytes extends SolidityType implements IType
{
    /**
     * construct
     * 
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * isType
     * 
     * @param string $name
     * @return bool
     */
    public function isType($name)
    {
        return (preg_match('/bytes([0-9]{1,})?(\[([0-9]*)\])*/', $name) === 1);
    }

    /**
     * isDynamicType
     * 
     * @return bool
     */
    public function isDynamicType()
    {
        return false;
    }

    /**
     * inputFormat
     * 
     * @param mixed $value
     * @param string $name
     * @return string
     */
    public function inputFormat($value, $name)
    {
        if (!Utils::isHex($value)) {
            throw new InvalidArgumentException('The value to inputFormat must be hex bytes.');
        }
        $value = Utils::stripZero($value);

        // if (mb_strlen($value) % 2 !== 0) {
        //     throw new InvalidArgumentException('The value to inputFormat has invalid length. Value: ' . $value);
        // }

        if (mb_strlen($value) > 64) {
            throw new InvalidArgumentException('The value to inputFormat is too long.');
        }
        $l = floor((mb_strlen($value) + 63) / 64);
        $padding = (($l * 64 - mb_strlen($value) + 1) >= 0) ? $l * 64 - mb_strlen($value) : 0;

        return $value . implode('', array_fill(0, $padding, '0'));
    }

    /**
     * outputFormat
     * 
     * @param mixed $value
     * @param string $name
     * @return string
     */
    public function outputFormat($value, $name)
    {
        $checkZero = str_replace('0', '', $value);

        if (empty($checkZero)) {
            return '0';
        }
        return '0x' . $value;
    }
}