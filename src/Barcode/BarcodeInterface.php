<?php

namespace Mpdf\Barcode;

interface BarcodeInterface
{
    // Array keys
    public const BCODE = 'bcode';
    public const CODE = 'code';
    public const MAXH = 'maxh';
    public const MAXW = 'maxw';

    public function getType(): string;

    public function getData(): array;

    /**
     * @return mixed
     */
    public function getKey(string $key);

    public function getChecksum(): string;

}
