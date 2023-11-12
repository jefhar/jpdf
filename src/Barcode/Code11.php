<?php

namespace Mpdf\Barcode;

/**
 * CODE11 barcodes.
 * Used primarily for labeling telecommunications equipment
 */
class Code11 extends AbstractBarcode
{
    protected array $data = [
        self::LIGHT_TB => 0,
        self::NOM_H => 10,
        self::NOM_X => 0.381,
    ];
    protected string $type = 'CODE11';
    private const CHARACTER_MAP = [
        '0' => '111121',
        '1' => '211121',
        '2' => '121121',
        '3' => '221111',
        '4' => '112121',
        '5' => '212111',
        '6' => '122111',
        '7' => '111221',
        '8' => '211211',
        '9' => '211111',
        '-' => '112111',
        'S' => '112211',
    ];

    /**
     * Code11 constructor.
     * @param string $code
     * @param float $printRatio
     * @param int|null $quiet_zone_left
     * @param int|null $quiet_zone_right
     * @throws BarcodeException
     */
    public function __construct(
        string $code,
        float $printRatio,
        ?int $quiet_zone_left = null,
        ?int $quiet_zone_right = null
    ) {
        $this->init($code, $printRatio);
        $this->data[self::LIGHT_ML] = ($quiet_zone_left !== null ? $quiet_zone_left : 10);
        $this->data[self::LIGHT_MR] = ($quiet_zone_right !== null ? $quiet_zone_right : 10);
    }

    /**
     * @param string $code
     * @param float $printRatio
     * @throws BarcodeException
     */
    private function init(string $code, float $printRatio)
    {
        $barArray = [
            self::BCODE => [],
            self::CODE => $code,
            self::MAX_H => 1,
            self::MAX_W => 0,
        ];

        $k = 0;

        $stringLength = strlen($code);
        // calculate check digit C

        $p = 1;
        $check = 0;

        for ($i = ($stringLength - 1); $i >= 0; --$i) {
            $digit = $code[$i];
            if ($digit === '-') {
                $digitValue = 10;
            } else {
                $digitValue = (int)$digit;
            }
            $check += ($digitValue * $p);
            ++$p;
            if ($p > 10) {
                $p = 1;
            }
        }

        $check %= 11;

        if ($check == 10) {
            $check = '-';
        }

        $code .= $check;
        $checkDigit = $check;

        if ($stringLength > 10) {
            // calculate check digit K
            $p = 1;
            $check = 0;
            for ($i = $stringLength; $i >= 0; --$i) {
                $digit = $code[$i];
                if ($digit == '-') {
                    $digitValue = 10;
                } else {
                    $digitValue = (int)$digit;
                }
                $check += ($digitValue * $p);
                ++$p;
                if ($p > 9) {
                    $p = 1;
                }
            }
            $check %= 11;
            $code .= $check;
            $checkDigit .= $check;
            ++$stringLength;
        }

        $code = 'S' . $code . 'S';
        $stringLength += 3;

        for ($i = 0; $i < $stringLength; ++$i) {
            if (!isset($chr[$code[$i]])) {
                throw new BarcodeException(
                    sprintf('Invalid character "%s" in CODE11 barcode value "%s"', $code[$i], $code)
                );
            }

            $seq = $chr[$code[$i]];

            for ($j = 0; $j < 6; ++$j) {
                $t = $j % 2 === 0;
                $x = $seq[$j];
                $w = ($x == 2) ? $printRatio : 1;

                $barArray[self::BCODE][$k] = ['t' => $t, 'w' => $w, 'h' => 1, 'p' => 0];
                $barArray[self::MAX_W] += $w;

                ++$k;
            }
        }

        $barArray[self::CHECK_DIGIT] = $checkDigit;

        $this->data = $barArray;
    }
}
