<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class jobZenTest extends baseTest
{
    protected $moduleName = 'job';
    protected $className  = 'zen';

    /**
     * Test buildSearchForm method.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest($searchConfig = array(), $queryID = '', $actionURL = '')
    {
        $result = $this->invokeArgs('buildSearchForm', [$searchConfig, $queryID, $actionURL]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
