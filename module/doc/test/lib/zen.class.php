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

    /**
     * Test prepareCols method.
     *
     * @param  array     $cols
     * @access public
     * @return array
     */
    public function prepareColsTest(array $cols)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $this->instance->view->cols = $cols;
        $result = $this->invokeArgs('prepareCols', []);
        if(dao::isError()) return dao::getError();
        return $this->instance->view->cols;
    }

    /**
     * Test previewCaselib method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewCaselibTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewCaselib', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previeweicket method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previeweicketTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        try
        {
            $result = $this->invokeArgs('previeweicket', [$view, $settings, $idList]);
            if(dao::isError()) return dao::getError();
            $data = isset($this->instance->view->data) ? $this->instance->view->data : array();
            if($data === false) $data = array();
            return array('cols' => isset($this->instance->view->cols) ? $this->instance->view->cols : array(), 'data' => $data);
        }
        catch(Exception $e)
        {
            return array('cols' => array(), 'data' => array());
        }
    }

    /**
     * Test previewER method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewERTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewER', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewExecutionStory method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewExecutionStoryTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewExecutionStory', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewFeedback method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewFeedbackTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewFeedback', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewPlanBug method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewPlanBugTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewPlanBug', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewPlanStory method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewPlanStoryTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewPlanStory', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewProductBug method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewProductBugTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewProductBug', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewProductCase method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewProductCaseTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewProductCase', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewProductplan method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewProductplanTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewProductplan', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewProductStory method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewProductStoryTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewProductStory', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewProjectStory method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewProjectStoryTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewProjectStory', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewStory method.
     *
     * @param  string $storyType
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewStoryTest(string $storyType, string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewStory', [$storyType, $view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewTask method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewTaskTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewTask', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }

    /**
     * Test previewUR method.
     *
     * @param  string $view
     * @param  array  $settings
     * @param  string $idList
     * @access public
     * @return array
     */
    public function previewURTest(string $view, array $settings, string $idList)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('previewUR', [$view, $settings, $idList]);
        if(dao::isError()) return dao::getError();
        return array('cols' => $this->instance->view->cols, 'data' => $this->instance->view->data);
    }
}
