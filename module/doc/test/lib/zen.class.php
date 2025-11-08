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
}
