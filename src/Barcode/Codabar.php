<?php

namespace Mpdf\Barcode;

/**
 * CODABAR barcodes.
 * Older code often used in library systems, sometimes in blood banks
 */
class Codabar extends AbstractBarcode
{
    protected array $data = [
        self::LIGHT_TB => 0,
        self::NOM_H => 10,
        self::NOM_X => 0.381,
    ];
    protected string $type = 'CODABAR';
    private const CHARACTER_MAP = [
        '0' => '11111221',
        '1' => '11112211',
        '2' => '11121121',
        '3' => '22111111',
        '4' => '11211211',
        '5' => '21111211',
        '6' => '12111121',
        '7' => '12112111',
        '8' => '12211111',
        '9' => '21121111',
        '-' => '11122111',
        '$' => '11221111',
        ':' => '21112121',
        '/' => '21211121',
        '.' => '21212111',
        '+' => '11222221',
        'A' => '11221211',
        'B' => '12121121',
        'C' => '11121221',
        'D' => '11122211',
    ];

    /**
     * Codabar constructor.
     * @param string $code
     * @param float $printRatio
     * @param int|null $quietZoneLeft
     * @param int|null $quietZoneRight
     * @throws BarcodeException
     */
    public function __construct(
        string $code,
        float $printRatio,
        ?int $quietZoneLeft = null,
        ?int $quietZoneRight = null
    ) {
        $this->init(strtoupper($code), $printRatio);

        $this->data[self::LIGHT_ML] = ($quietZoneLeft !== null ? $quietZoneLeft : 10);
        $this->data[self::LIGHT_MR] = ($quietZoneRight !== null ? $quietZoneRight : 10);
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
        $element = 0;

        $stringLength = strlen($code);

        for ($i = 0; $i < $stringLength; ++$i) {
            if (!isset(self::CHARACTER_MAP[$code[$i]])) {
                throw new BarcodeException(
                    sprintf(
                        'Invalid character "%s" in CODABAR barcode value "%s"',
                        $code[$i],
                        $code
                    )
                );
            }

            $character = self::CHARACTER_MAP[$code[$i]];

            for ($j = 0; $j < 8; ++$j) {
                $t = ($j % 2) === 0; // true is bar, false is space
                $width = $character[$j] === 2 ? $printRatio : 1;
                $barArray[self::BCODE][$element] = ['t' => $t, 'w' => $width, 'h' => 1, 'p' => 0];
                $barArray[self::MAX_W] += $width;
                ++$element;
            }
        }

        $this->data = $barArray;
    }
}
