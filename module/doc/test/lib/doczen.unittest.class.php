<?php
class docZenTest
{
    public $docZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('doc');

        $this->objectModel  = $tester->loadModel('doc');
        $this->docZenTest = initReference('doc');
    }

    /**
     * Test processReleaseListData method.
     *
     * @param  array $releaseList
     * @param  array $childReleases
     * @access public
     * @return array
     */
    public function processReleaseListDataTest(array $releaseList, array $childReleases): array
    {
        $result = callZenMethod('doc', 'processReleaseListData', array($releaseList, $childReleases));

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test assignApiVarForSpace method.
     *
     * @param  string $type
     * @param  string $browseType
     * @param  string $libType
     * @param  int    $libID
     * @param  array  $libs
     * @param  int    $objectID
     * @param  int    $moduleID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return object
     */
    public function assignApiVarForSpaceTest(string $type = 'product', string $browseType = 'all', string $libType = 'lib', int $libID = 0, array $libs = array(), int $objectID = 0, int $moduleID = 0, int $queryID = 0, string $orderBy = 'id_desc', int $param = 0, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): object
    {
        ob_start();
        $view = callZenMethod('doc', 'assignApiVarForSpace', array($type, $browseType, $libType, $libID, $libs, $objectID, $moduleID, $queryID, $orderBy, $param, $recTotal, $recPerPage, $pageID), 'view');
        ob_end_clean();

        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->pager = isset($view->pager) ? get_class($view->pager) : '';
        $result->canExport = isset($view->canExport) ? (int)$view->canExport : 0;

        if($libType == 'api')
        {
            $result->hasApiList = isset($view->apiList) ? 1 : 0;
            $result->hasLibs = isset($view->libs) ? 1 : 0;
        }
        else
        {
            $result->hasDocs = isset($view->docs) ? 1 : 0;
        }

        return $result;
    }

    /**
     * Test assignStoryGradeData method.
     *
     * @param  string $type
     * @access public
     * @return object
     */
    public function assignStoryGradeDataTest(string $type): object
    {
        ob_start();
        $view = callZenMethod('doc', 'assignStoryGradeData', array($type), 'view');
        ob_end_clean();

        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->hasGradeGroup = isset($view->gradeGroup) ? 1 : 0;
        $result->hasStoryType = isset($view->storyType) ? 1 : 0;
        $result->storyType = isset($view->storyType) ? $view->storyType : '';
        $result->gradeGroupCount = isset($view->gradeGroup) ? count($view->gradeGroup) : 0;

        return $result;
    }
}