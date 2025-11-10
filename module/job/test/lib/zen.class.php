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

    /**
     * Test getCompileData method.
     *
     * @param  object $compile
     * @access public
     * @return array
     */
    public function getCompileDataTest($compile)
    {
        $this->invokeArgs('getCompileData', [$compile]);
        if(dao::isError()) return array('error' => dao::getError());

        $view = $this->getProperty('view');
        if(!$view) return array('error' => 'View not found');

        $result = array(
            'groupCases' => isset($view->groupCases) ? count($view->groupCases) : 0,
            'suites'     => isset($view->suites) ? count($view->suites) : 0,
            'summary'    => isset($view->summary) ? count($view->summary) : 0,
            'taskID'     => isset($view->taskID) ? $view->taskID : 0
        );

        return $result;
    }
}
