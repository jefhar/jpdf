<?php

namespace Mpdf\Barcode;

interface BarcodeInterface
{
    public function getType(): string;

    public function getData(): array;

    /**
     * @return mixed
     */
    public function getKey(string $key);

    public function getChecksum(): string;

}
