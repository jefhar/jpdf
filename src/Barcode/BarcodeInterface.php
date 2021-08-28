<?php

namespace Mpdf\Barcode;

interface BarcodeInterface
{
    // Array keys
    public const BCODE = 'bcode';
    public const CHECK_DIGIT = 'checkdigit';
    public const CODE = 'code';
    public const LIGHT_ML = 'lightmL';
    public const LIGHT_MR = 'lightmR';
    public const LIGHT_TB = 'lightTB';
    public const MAX_H = 'maxh';
    public const MAX_W = 'maxw';
    public const NOM_H = 'nom-H';
    public const NOM_X = 'nom-X';
    public const QUIET_L = 'quietL';
    public const QUIET_R = 'quietR';
    public const QUIET_TB = 'quietTB';
    public const SEP_M = 'sepM';

    public function getType(): string;

    public function getData(): array;

    /**
     * @return mixed
     */
    public function getKey(string $key);

    public function getChecksum(): string;

}
