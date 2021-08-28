<?php

namespace Mpdf\Barcode;

interface BarcodeInterface
{
    // Array keys
    public const BCODE = 'bcode';
    public const CHECKDIGIT = 'checkdigit';
    public const CODE = 'code';
    public const LIGHTML = 'lightmL';
    public const LIGHTMR = 'lightmR';
    public const MAXH = 'maxh';
    public const MAXW = 'maxw';
    public const NOMH = 'nom-H';
    public const NOMX = 'nom-X';
    public const QUIETL = 'quietL';
    public const QUIETR = 'quietR';
    public const QUIETTB = 'quietTB';
    public const SEPM = 'sepM';

    public function getType(): string;

    public function getData(): array;

    /**
     * @return mixed
     */
    public function getKey(string $key);

    public function getChecksum(): string;

}
