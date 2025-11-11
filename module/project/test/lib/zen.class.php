<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class projectZenTest extends baseTest
{
    protected $moduleName = 'project';
    protected $className  = 'zen';

    /**
     * Test checkWorkdaysLegtimate method.
     *
     * @param  object $project 项目对象
     * @access public
     * @return bool|string
     */
    public function checkWorkdaysLegtimateTest($project = null)
    {
        $result = $this->invokeArgs('checkWorkdaysLegtimate', [$project]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test displayAfterCreated method.
     *
     * @param  int $projectID 项目ID
     * @access public
     * @return mixed
     */
    public function displayAfterCreatedTest($projectID = 0)
    {
        global $tester;
        ob_start();
        $result = $this->invokeArgs('displayAfterCreated', [$projectID]);
        ob_end_clean();

        $view = $tester->view;
        if(dao::isError()) return dao::getError();
        return $view;
    }

    /**
     * Test expandExecutionIdList method.
     *
     * @param  mixed $stats 执行统计数据
     * @access public
     * @return array
     */
    public function expandExecutionIdListTest($stats = null)
    {
        $result = $this->invokeArgs('expandExecutionIdList', [$stats]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test extractUnModifyForm method.
     *
     * @param  int    $projectID 项目ID
     * @param  object $project   项目对象
     * @access public
     * @return object
     */
    public function extractUnModifyFormTest($projectID = 0, $project = null)
    {
        global $tester;
        ob_start();
        $result = $this->invokeArgs('extractUnModifyForm', [$projectID, $project]);
        ob_end_clean();

        $view = $tester->view;
        if(dao::isError()) return dao::getError();
        return $view;
    }

    /**
     * Test getKanbanData method.
     *
     * @access public
     * @return array
     */
    public function getKanbanDataTest()
    {
        $result = $this->invokeArgs('getKanbanData', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
