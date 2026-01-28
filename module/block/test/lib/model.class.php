<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class blockModelTest extends baseTest
{
    protected $moduleName = 'block';
    protected $className  = 'model';

    /**
     * Test create a block.
     *
     * @param  object $block
     * @access public
     * @return int|false
     */
    public function createTest($block)
    {
        $blockID = $this->instance->create($block);
        if(dao::isError()) return dao::getError();
        return $blockID;
    }

    /**
     * Test getBlockInitStatus method.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function getBlockInitStatusTest($dashboard)
    {
        $result = $this->instance->getBlockInitStatus($dashboard);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getModelType4Projects method.
     *
     * @param  array $projectIdList
     * @access public
     * @return string
     */
    public function getModelType4ProjectsTest($projectIdList = array())
    {
        $result = $this->invokeArgs('getModelType4Projects', [$projectIdList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getMyDashboard method.
     *
     * @param  string $dashboard
     * @access public
     * @return object
     */
    public function getMyDashboardTest(string $dashboard)
    {
        $result = $this->instance->getMyDashboard($dashboard);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getSpecifiedBlockID method.
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $code
     * @access public
     * @return int|false
     */
    public function getSpecifiedBlockIDTest(string $dashboard, string $module, string $code)
    {
        $result = $this->instance->getSpecifiedBlockID($dashboard, $module, $code);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test reset method.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function resetTest(string $dashboard)
    {
        $result = $this->instance->reset($dashboard);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Update a block.
     *
     * @param  object $block
     * @access public
     * @return int|false
     */
    public function updateTest($block)
    {
        $blockID = $this->instance->update($block);
        if(dao::isError()) return dao::getError();
        return $blockID;
    }
}
