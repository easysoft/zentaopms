<?php
declare(strict_types = 1);
class programTest
{
    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('program');
        $tester->loadModel('program');

        $this->objectZen = initReference('program');
    }

    /**
     * Test getPMListByPrograms method.
     *
     * @param  array $programs
     * @access public
     * @return mixed
     */
    public function getPMListByProgramsTest($programs = array())
    {
        $method = $this->objectZen->getMethod('getPMListByPrograms');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($programs));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProgramForCreate method.
     *
     * @access public
     * @return object|array
     */
    public function buildProgramForCreateTest()
    {
        $method = $this->objectZen->getMethod('buildProgramForCreate');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array());
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProgramForEdit method.
     *
     * @param  int $programID
     * @access public
     * @return object|array
     */
    public function buildProgramForEditTest(int $programID)
    {
        $method = $this->objectZen->getMethod('buildProgramForEdit');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($programID));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildTree method.
     *
     * @param  array $programs
     * @param  int   $parentID
     * @access public
     * @return array
     */
    public function buildTreeTest(array $programs, int $parentID = 0)
    {
        $method = $this->objectZen->getMethod('buildTree');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($programs, $parentID));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getKanbanList method.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getKanbanListTest(string $browseType = 'my')
    {
        $method = $this->objectZen->getMethod('getKanbanList');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($browseType));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLink method.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $programID
     * @param  string $vars
     * @param  string $from
     * @access public
     * @return string
     */
    public function getLinkTest(string $moduleName, string $methodName, string $programID, string $vars = '', string $from = 'program')
    {
        $method = $this->objectZen->getMethod('getLink');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($moduleName, $methodName, $programID, $vars, $from));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductsByBrowseType method.
     *
     * @param  string $browseType
     * @param  array  $products
     * @access public
     * @return array
     */
    public function getProductsByBrowseTypeTest(string $browseType, array $products)
    {
        $method = $this->objectZen->getMethod('getProductsByBrowseType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($browseType, $products));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProgramList4Kanban method.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getProgramList4KanbanTest(string $browseType = 'my')
    {
        $method = $this->objectZen->getMethod('getProgramList4Kanban');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($browseType));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProgramsByType method.
     *
     * @param  string      $status
     * @param  string      $orderBy
     * @param  int         $param
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getProgramsByTypeTest(string $status, string $orderBy, int $param = 0, ?object $pager = null)
    {
        $method = $this->objectZen->getMethod('getProgramsByType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($status, $orderBy, $param, $pager));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test prepareStartExtras method.
     *
     * @param  array $data
     * @access public
     * @return object
     */
    public function prepareStartExtrasTest(array $data = array())
    {
        $fixer = new fixer('post');
        $fixer->data = (object)$data;

        $method = $this->objectZen->getMethod('prepareStartExtras');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($fixer));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test removeSubjectToCurrent method.
     *
     * @param  array $parents
     * @param  int   $programID
     * @access public
     * @return array
     */
    public function removeSubjectToCurrentTest(array $parents, int $programID)
    {
        $method = $this->objectZen->getMethod('removeSubjectToCurrent');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), array($parents, $programID));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}