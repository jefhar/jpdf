<?php

namespace Mpdf\Barcode;

interface BarcodeInterface
{
    // Array keys
    public const BCODE = 'bcode';
    public const CHECK_DIGIT = 'checkdigit';
    public const CODE = 'code';

    // LEFT light margin =  x X-dim (spec.)
    public const LIGHT_ML = 'lightmL';

    // RIGHT light margin =  x X-dim (spec.)
    public const LIGHT_MR = 'lightmR';

    // TOP/BOTTOM light margin =  x X-dim (non-spec.)
    public const LIGHT_TB = 'lightTB';
    public const MAX_H = 'maxh';
    public const MAX_W = 'maxw';

// Nominal value for Height of Full bar in mm (non-spec.)
    public const NOM_H = 'nom-H';

    // Nominal value for X-dim (bar width) in mm (2 X min. spec.)
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
