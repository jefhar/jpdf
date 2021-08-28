<?php

namespace Mpdf\Barcode;

/**
 * @group unit
 */
class ImbTest extends \PHPUnit_Framework_TestCase
{

	public function testInit()
	{
		$xdim = 0.508; // Nominal value for X-dim (bar width) in mm (spec.)
		$bpi = 22; // Bars per inch

		$barcode = new Imb('01234567094987654321-01234567891', $xdim, ((25.4 / $bpi) - $xdim) / $xdim, ['D' => 2, 'A' => 2, 'F' => 3, 'T' => 1]);

		$array = $barcode->getData();

		$this->assertInternalType('array', $array);
		$this->assertArrayHasKey(BarcodeInterface::MAX_H, $array);
		$this->assertGreaterThan(0, $array[BarcodeInterface::MAX_H]);

		$this->assertNull($barcode->getChecksum());
	}

}
