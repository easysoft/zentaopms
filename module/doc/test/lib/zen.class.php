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

    /**
     * Test processFiles method.
     *
     * @param  array $files
     * @param  array $fileIcon
     * @param  array $sourcePairs
     * @param  bool  $skipImageWidth
     * @access public
     * @return array
     */
    public function processFilesTest(array $files, array $fileIcon, array $sourcePairs, bool $skipImageWidth = false)
    {
        $result = $this->invokeArgs('processFiles', [$files, $fileIcon, $sourcePairs, $skipImageWidth]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processOutline method.
     *
     * @param  object $doc
     * @access public
     * @return object
     */
    public function processOutlineTest(object $doc)
    {
        if(!isset($this->instance->view)) $this->instance->view = new stdClass();
        $result = $this->invokeArgs('processOutline', [$doc]);
        if(dao::isError()) return dao::getError();

        /* Extract anchor IDs for easier testing. */
        preg_match_all("/id='anchor(\d+)'/", $result->content, $matches);
        $anchors = isset($matches[1]) ? $matches[1] : array();

        /* Create a simplified result for testing. */
        $testResult = new stdClass();
        $testResult->anchorCount = count($anchors);
        $testResult->anchor0     = isset($anchors[0]) ? $anchors[0] : '';
        $testResult->anchor1     = isset($anchors[1]) ? $anchors[1] : '';
        $testResult->anchor2     = isset($anchors[2]) ? $anchors[2] : '';
        $testResult->hasH1       = (int)(strpos($result->content, '<h1') !== false);
        $testResult->hasH2       = (int)(strpos($result->content, '<h2') !== false);
        $testResult->hasH3       = (int)(strpos($result->content, '<h3') !== false);
        $testResult->content     = str_replace("\n", "", $result->content); // Remove newlines for easier matching

        return $testResult;
    }

    /**
     * Test recordBatchMoveActions method.
     *
     * @param  array  $oldDocList
     * @param  object $data
     * @access public
     * @return int
     */
    public function recordBatchMoveActionsTest(array $oldDocList, object $data)
    {
        $result = $this->invokeArgs('recordBatchMoveActions', [$oldDocList, $data]);
        if(dao::isError()) return dao::getError();

        /* Get the count of created actions for this batch. */
        global $tester;
        $actionCount = $tester->dao->select('count(1) as count')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('action')->eq('Moved')
            ->fetch('count');

        return (int)$actionCount;
    }

    /**
     * Test responseAfterAddTemplateType method.
     *
     * @param  int $scope
     * @access public
     * @return object
     */
    public function responseAfterAddTemplateTypeTest(int $scope)
    {
        /* Set viewType to json to get json response. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = 'json';

        try
        {
            $result = $this->invokeArgs('responseAfterAddTemplateType', [$scope]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterCreate method.
     *
     * @param  array  $docResult
     * @param  string $objectType
     * @param  string $viewType
     * @access public
     * @return object
     */
    public function responseAfterCreateTest(array $docResult, string $objectType = 'doc', string $viewType = 'json')
    {
        /* Set viewType. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = $viewType;

        try
        {
            $result = $this->invokeArgs('responseAfterCreate', [$docResult, $objectType]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterCreateLib method.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $libID
     * @param  string $libName
     * @param  string $orderBy
     * @param  string $viewType
     * @access public
     * @return object
     */
    public function responseAfterCreateLibTest(string $type = '', int $objectID = 0, int $libID = 0, string $libName = '', string $orderBy = '', string $viewType = 'json')
    {
        /* Set viewType. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = $viewType;

        try
        {
            $result = $this->invokeArgs('responseAfterCreateLib', [$type, $objectID, $libID, $libName, $orderBy]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterEdit method.
     *
     * @param  object $doc
     * @param  array  $changes
     * @param  array  $files
     * @param  array  $postData
     * @access public
     * @return object
     */
    public function responseAfterEditTest(object $doc, array $changes = array(), array $files = array(), array $postData = array())
    {
        /* Set POST data. */
        $_POST['comment'] = isset($postData['comment']) ? $postData['comment'] : '';
        $_POST['status']  = isset($postData['status']) ? $postData['status'] : $doc->status;

        /* Set viewType. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = 'json';

        try
        {
            $result = $this->invokeArgs('responseAfterEdit', [$doc, $changes, $files]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        /* Clean up POST data. */
        unset($_POST['comment'], $_POST['status']);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterEditTemplate method.
     *
     * @param  object $doc
     * @param  array  $changes
     * @param  array  $files
     * @param  array  $postData
     * @access public
     * @return object
     */
    public function responseAfterEditTemplateTest(object $doc, array $changes = array(), array $files = array(), array $postData = array())
    {
        /* Set POST data. */
        $_POST['comment'] = isset($postData['comment']) ? $postData['comment'] : '';
        $_POST['status']  = isset($postData['status']) ? $postData['status'] : $doc->status;

        /* Set viewType. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = 'json';

        try
        {
            $result = $this->invokeArgs('responseAfterEditTemplate', [$doc, $changes, $files]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        /* Clean up POST data. */
        unset($_POST['comment'], $_POST['status']);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterMove method.
     *
     * @param  string $space
     * @param  int    $libID
     * @param  int    $docID
     * @param  bool   $spaceTypeChanged
     * @access public
     * @return object
     */
    public function responseAfterMoveTest(string $space, int $libID = 0, int $docID = 0, bool $spaceTypeChanged = false)
    {
        /* Set viewType to json to get json response. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = 'json';

        try
        {
            $result = $this->invokeArgs('responseAfterMove', [$space, $libID, $docID, $spaceTypeChanged]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterUploadDocs method.
     *
     * @param  array|string $docResult
     * @param  array        $postData
     * @param  string       $viewType
     * @access public
     * @return object
     */
    public function responseAfterUploadDocsTest(array|string $docResult, array $postData = array(), string $viewType = 'json')
    {
        /* Set POST data. */
        $_POST['uploadFormat'] = isset($postData['uploadFormat']) ? $postData['uploadFormat'] : 'separateDocs';

        /* Set viewType. */
        $originalViewType = $this->instance->viewType;
        $this->instance->viewType = $viewType;

        try
        {
            $result = $this->invokeArgs('responseAfterUploadDocs', [$docResult]);
        }
        catch(EndResponseException $e)
        {
            $content = $e->getContent();
            $result  = json_decode($content);
        }

        /* Restore viewType. */
        $this->instance->viewType = $originalViewType;

        /* Clean up POST data. */
        unset($_POST['uploadFormat']);

        if(dao::isError()) return dao::getError();
        return $result;
    }
}
