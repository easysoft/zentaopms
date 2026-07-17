<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 2) . '/lib/model.class.php';

class ppmTaoTest extends ppmBaseTest
{
    protected $moduleName = 'ppm';
    protected $className  = 'tao';

    public function getLinkedObjectPairsTest(int $ppmID, string $objectType = 'story', string $rawModule = 'ppm')
    {
        return $this->invokeMethod('getLinkedObjectPairs', array($ppmID, $objectType), $rawModule);
    }
}
