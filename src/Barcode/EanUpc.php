<?php

namespace Mpdf\Barcode;

/**
 * EAN13 and UPC-A barcodes.
 * EAN13: European Article Numbering international retail product code
 * UPC-A: Universal product code seen on almost all retail products in the USA and Canada
 * UPC-E: Short version of UPC symbol
 */
class EanUpc extends AbstractBarcode
{
    protected string $type = 'EANUPC';

    /**
     * @param string $code
     * @param int $length
     * @param float $leftMargin
     * @param float $rightMargin
     * @param float $xDim
     * @param float $barHeight
     */
    public function __construct(string $code,
        int $length, float $leftMargin, float $rightMargin, float $xDim, float $barHeight)
    {
        $this->init($code, $length);

        $this->data[BarcodeInterface::LIGHT_ML] = $leftMargin;
        $this->data[BarcodeInterface::LIGHT_MR] = $rightMargin;
        $this->data[BarcodeInterface::NOM_H] = $barHeight;
        $this->data[BarcodeInterface::NOM_X] = $xDim;
    }

    /**
     * @param string $code
     * @param int $length
     */
    private function init(string $code, int $length)
    {
        if (preg_match('/[\D]+/', $code)) {
            throw new BarcodeException(sprintf('Invalid EAN UPC barcode value "%s"', $code));
        }

        $upcE = false;
        $checkDigit = false;

        if ($length === 6) {
            $length = 12; // UPC-A
            $upcE = true; // UPC-E mode
        }
        $dataLength = $length - 1;

        // Padding
        $code = str_pad($code, $dataLength, '0', STR_PAD_LEFT);
        $codeLength = strlen($code);

        // Calculate check digit
        $sum_a = 0;
        for ($i = 1; $i < $dataLength; $i += 2) {
            $sum_a += $code[$i];
        }

        if ($length > 12) {
            $sum_a *= 3;
        }
        $sum_b = 0;
        for ($i = 0; $i < $dataLength; $i += 2) {
            $sum_b += ($code[$i]);
        }

        if ($length < 13) {
            $sum_b *= 3;
        }

        $r = ($sum_a + $sum_b) % 10;
        if ($r > 0) {
            $r = (10 - $r);
        }

        if ($codeLength == $dataLength) {
            // Add check digit
            $code .= $r;
            $checkDigit = $r;
        } elseif ($r !== (int)$code[$dataLength]) {
            // Wrong checkdigit
            throw new BarcodeException(sprintf('Invalid EAN UPC barcode value "%s"', $code));
        }

        if ($length == 12) {
            // UPC-A
            $code = '0' . $code;
            ++$length;
        }

        if ($upcE) {
            // Convert UPC-A to UPC-E
            $tmp = substr($code, 4, 3);
            $prodCode = (int)substr($code, 7, 5); // product code
            $invalidUpce = false;
            if (($tmp == '000') or ($tmp == '100') or ($tmp == '200')) {
                // Manufacturer code ends in 000, 100, or 200
                $upceCode = substr($code, 2, 2) . substr($code, 9, 3) . substr($code, 4, 1);
                if ($prodCode > 999) {
                    $invalidUpce = true;
                }
            } else {
                $tmp = substr($code, 5, 2);
                if ($tmp == '00') {
                    // Manufacturer code ends in 00
                    $upceCode = substr($code, 2, 3) . substr($code, 10, 2) . '3';
                    if ($prodCode > 99) {
                        $invalidUpce = true;
                    }
                } else {
                    $tmp = substr($code, 6, 1);
                    if ($tmp == '0') {
                        // Manufacturer code ends in 0
                        $upceCode = substr($code, 2, 4) . substr($code, 11, 1) . '4';
                        if ($prodCode > 9) {
                            $invalidUpce = true;
                        }
                    } else {
                        // Manufacturer code does not end in zero
                        $upceCode = substr($code, 2, 5) . substr($code, 11, 1);
                        if ($prodCode > 9) {
                            $invalidUpce = true;
                        }
                    }
                }
            }

            if ($invalidUpce) {
                throw new BarcodeException('UPC-A cannot produce a valid UPC-E barcode');
            }
        }

        // Convert digits to bars
        $codes = [
            'A' => [// left odd parity
                '0' => '0001101',
                '1' => '0011001',
                '2' => '0010011',
                '3' => '0111101',
                '4' => '0100011',
                '5' => '0110001',
                '6' => '0101111',
                '7' => '0111011',
                '8' => '0110111',
                '9' => '0001011',
            ],
            'B' => [// left even parity
                '0' => '0100111',
                '1' => '0110011',
                '2' => '0011011',
                '3' => '0100001',
                '4' => '0011101',
                '5' => '0111001',
                '6' => '0000101',
                '7' => '0010001',
                '8' => '0001001',
                '9' => '0010111',
            ],
            'C' => [// right
                '0' => '1110010',
                '1' => '1100110',
                '2' => '1101100',
                '3' => '1000010',
                '4' => '1011100',
                '5' => '1001110',
                '6' => '1010000',
                '7' => '1000100',
                '8' => '1001000',
                '9' => '1110100',
            ],
        ];

        $parities = [
            '0' => ['A', 'A', 'A', 'A', 'A', 'A'],
            '1' => ['A', 'A', 'B', 'A', 'B', 'B'],
            '2' => ['A', 'A', 'B', 'B', 'A', 'B'],
            '3' => ['A', 'A', 'B', 'B', 'B', 'A'],
            '4' => ['A', 'B', 'A', 'A', 'B', 'B'],
            '5' => ['A', 'B', 'B', 'A', 'A', 'B'],
            '6' => ['A', 'B', 'B', 'B', 'A', 'A'],
            '7' => ['A', 'B', 'A', 'B', 'A', 'B'],
            '8' => ['A', 'B', 'A', 'B', 'B', 'A'],
            '9' => ['A', 'B', 'B', 'A', 'B', 'A'],
        ];

        $upceParities = [];
        $upceParities[0] = [
            '0' => ['B', 'B', 'B', 'A', 'A', 'A'],
            '1' => ['B', 'B', 'A', 'B', 'A', 'A'],
            '2' => ['B', 'B', 'A', 'A', 'B', 'A'],
            '3' => ['B', 'B', 'A', 'A', 'A', 'B'],
            '4' => ['B', 'A', 'B', 'B', 'A', 'A'],
            '5' => ['B', 'A', 'A', 'B', 'B', 'A'],
            '6' => ['B', 'A', 'A', 'A', 'B', 'B'],
            '7' => ['B', 'A', 'B', 'A', 'B', 'A'],
            '8' => ['B', 'A', 'B', 'A', 'A', 'B'],
            '9' => ['B', 'A', 'A', 'B', 'A', 'B'],
        ];

        $upceParities[1] = [
            '0' => ['A', 'A', 'A', 'B', 'B', 'B'],
            '1' => ['A', 'A', 'B', 'A', 'B', 'B'],
            '2' => ['A', 'A', 'B', 'B', 'A', 'B'],
            '3' => ['A', 'A', 'B', 'B', 'B', 'A'],
            '4' => ['A', 'B', 'A', 'A', 'B', 'B'],
            '5' => ['A', 'B', 'B', 'A', 'A', 'B'],
            '6' => ['A', 'B', 'B', 'B', 'A', 'A'],
            '7' => ['A', 'B', 'A', 'B', 'A', 'B'],
            '8' => ['A', 'B', 'A', 'B', 'B', 'A'],
            '9' => ['A', 'B', 'B', 'A', 'B', 'A'],
        ];

        $k = 0;
        $seq = '101'; // left guard bar

        if ($upcE && isset($upceCode)) {
            $barArray = [
                self::BCODE => [],
                self::CODE => $upceCode,
                self::MAX_H => 1,
                self::MAX_W => 0,
            ];
            $parity = $upceParities[$code[1]][$r];
            for ($i = 0; $i < 6; ++$i) {
                $seq .= $codes[$parity[$i]][$upceCode[$i]];
            }
            $seq .= '010101'; // right guard bar
        } else {
            $barArray = [
                self::BCODE => [],
                self::CODE => $code,
                self::MAX_H => 1,
                self::MAX_W => 0,
            ];
            $halfStringLength = ceil($length / 2);
            if ($length == 8) {
                for ($i = 0; $i < $halfStringLength; ++$i) {
                    $seq .= $codes['A'][$code[$i]];
                }
            } else {
                $parity = $parities[$code[0]];
                for ($i = 1; $i < $halfStringLength; ++$i) {
                    $seq .= $codes[$parity[$i - 1]][$code[$i]];
                }
            }
            $seq .= '01010'; // center guard bar
            for ($i = $halfStringLength; $i < $length; ++$i) {
                $seq .= $codes['C'][$code[(int)$i]];
            }
            $seq .= '101'; // right guard bar
        }

        $clen = strlen($seq);
        $w = 0;
        for ($i = 0; $i < $clen; ++$i) {
            $w += 1;
            if (($i == ($clen - 1)) or (($i < ($clen - 1)) and ($seq[$i] != $seq[($i + 1)]))) {
                $t = $seq[$i] === '1';
                $barArray[BarcodeInterface::BCODE][$k] = ['t' => $t, 'w' => $w, 'h' => 1, 'p' => 0];
                $barArray[BarcodeInterface::MAX_W] += $w;
                ++$k;
                $w = 0;
            }
        }
        $barArray[BarcodeInterface::CHECK_DIGIT] = $checkDigit;

        $this->data = $barArray;
    }

}
