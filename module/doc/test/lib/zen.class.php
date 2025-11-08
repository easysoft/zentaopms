<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class docZenTest extends baseTest
{
    protected $moduleName = 'doc';
    protected $className  = 'zen';

    /**
     * Test buildOutlineList method.
     *
     * @param  int       $topLevel
     * @param  array     $content
     * @param  array     $includeHeadElement
     * @access public
     * @return array
     */
    public function buildOutlineListTest(int $topLevel = 1, array $content = array(), array $includeHeadElement = array())
    {
        $result = $this->invokeArgs('buildOutlineList', [$topLevel, $content, $includeHeadElement]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildOutlineTree method.
     *
     * @param  array     $outlineList
     * @param  int       $parentID
     * @access public
     * @return array
     */
    public function buildOutlineTreeTest(array $outlineList = array(), int $parentID = -1)
    {
        $result = $this->invokeArgs('buildOutlineTree', [$outlineList, $parentID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkPrivForCreate method.
     *
     * @param  object    $doclib
     * @param  string    $objectType
     * @access public
     * @return bool
     */
    public function checkPrivForCreateTest(object $doclib, string $objectType)
    {
        $result = $this->invokeArgs('checkPrivForCreate', [$doclib, $objectType]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formFromSession method.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function formFromSessionTest(string $type)
    {
        $result = $this->invokeArgs('formFromSession', [$type]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAllSpaces method.
     *
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getAllSpacesTest(string $extra = '')
    {
        $result = $this->invokeArgs('getAllSpaces', [$extra]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDocChildrenByRecursion method.
     *
     * @param  int    $docID
     * @param  int    $level
     * @access public
     * @return array
     */
    public function getDocChildrenByRecursionTest(int $docID, int $level)
    {
        $result = $this->invokeArgs('getDocChildrenByRecursion', [$docID, $level]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOutlineParentID method.
     *
     * @param  array     $outlineList
     * @param  int       $currentLevel
     * @access public
     * @return int
     */
    public function getOutlineParentIDTest(array $outlineList, int $currentLevel)
    {
        $result = $this->invokeArgs('getOutlineParentID', [$outlineList, $currentLevel]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initLibForMySpace method.
     *
     * @access public
     * @return void
     */
    public function initLibForMySpaceTest()
    {
        $result = $this->invokeArgs('initLibForMySpace', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initLibForTeamSpace method.
     *
     * @access public
     * @return void
     */
    public function initLibForTeamSpaceTest()
    {
        $result = $this->invokeArgs('initLibForTeamSpace', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
