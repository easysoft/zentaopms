<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class indexZenTest extends baseTest
{
    protected $moduleName = 'index';
    protected $className  = 'zen';

    /**
     * Test checkShowFeatures method.
     *
     * @access public
     * @return mixed
     */
    public function checkShowFeaturesTest()
    {
        $result = $this->invokeArgs('checkShowFeatures');
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getViewMethodForAssetLib method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return mixed
     */
    public function getViewMethodForAssetLibTest(int $objectID, string $objectType)
    {
        $result = $this->invokeArgs('getViewMethodForAssetLib', [$objectID, $objectType]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
