<?php

namespace Mpdf\Barcode;

abstract class AbstractBarcode
{

    protected array $data;

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getKey(string $key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getChecksum(): string
    {
        return $this->getKey('checkdigit');
    }

    /**
     * Convert binary barcode sequence to barcode array
     */
    protected function binseqToArray(string $sequence, array $barcodeData): array
    {
        $sequenceLength = strlen($sequence);
        $w = 0;
        $k = 0;
        for ($i = 0; $i < $sequenceLength; ++$i) {
            $w += 1;
            if (
                ($i == ($sequenceLength - 1))
                || (($i < ($sequenceLength - 1)) && ($sequence[$i] != $sequence[($i + 1)]))
            ) {
                $t = $sequence[$i] == '1';
                $barcodeData[BarcodeInterface::BCODE][$k] = ['t' => $t, 'w' => $w, 'h' => 1, 'p' => 0];
                $barcodeData['maxw'] += $w;
                ++$k;
                $w = 0;
            }
        }

        return $barcodeData;
    }

}
