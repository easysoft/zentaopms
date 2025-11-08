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

    /**
     * Test assignVarsForCreate method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $docType
     * @access public
     * @return object
     */
    public function assignVarsForCreateTest(string $objectType = 'product', int $objectID = 0, int $libID = 0, int $moduleID = 0, string $docType = ''): object
    {
        ob_start();
        $view = callZenMethod('doc', 'assignVarsForCreate', array($objectType, $objectID, $libID, $moduleID, $docType), 'view');
        ob_end_clean();

        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->objectType = isset($view->objectType) ? $view->objectType : '';
        $result->spaceType  = isset($view->spaceType) ? $view->spaceType : '';
        $result->type       = isset($view->type) ? $view->type : '';
        $result->libID      = isset($view->libID) ? (int)$view->libID : 0;
        $result->objectID   = isset($view->objectID) ? (int)$view->objectID : 0;
        $result->moduleID   = isset($view->moduleID) ? (int)$view->moduleID : 0;
        $result->docType    = isset($view->docType) ? $view->docType : '';
        $result->libName    = isset($view->libName) ? $view->libName : '';
        $result->hasLib     = isset($view->lib) ? 1 : 0;
        $result->hasLibs    = isset($view->libs) ? 1 : 0;
        $result->hasGroups  = isset($view->groups) ? 1 : 0;
        $result->hasUsers   = isset($view->users) ? 1 : 0;
        $result->hasObjects = isset($view->objects) ? 1 : 0;
        $result->hasOptionMenu = isset($view->optionMenu) ? 1 : 0;

        return $result;
    }

    /**
     * Test assignVarsForMySpace method.
     *
     * @access public
     * @return object
     */
    public function assignVarsForMySpaceTest(string $type = 'mine', int $objectID = 0, int $libID = 0, int $moduleID = 0, string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', array $docs = array(), object $pager = null, array $libs = array(), string $objectTitle = ''): object
    {
        if(is_null($pager))
        {
            global $tester;
            $tester->app->loadClass('pager', true);
            $oldErrorReporting = error_reporting(0);
            $pager = new pager(0, 20, 1);
            error_reporting($oldErrorReporting);
        }

        ob_start();
        $view = callZenMethod('doc', 'assignVarsForMySpace', array($type, $objectID, $libID, $moduleID, $browseType, $param, $orderBy, $docs, $pager, $libs, $objectTitle), 'view');
        ob_end_clean();

        if(dao::isError()) return (object)dao::getError();

        $result = new stdclass();
        $result->type = isset($view->type) ? $view->type : '';
        $result->libID = isset($view->libID) ? (int)$view->libID : 0;
        $result->moduleID = isset($view->moduleID) ? (int)$view->moduleID : 0;
        $result->browseType = isset($view->browseType) ? $view->browseType : '';
        $result->orderBy = isset($view->orderBy) ? $view->orderBy : '';
        $result->objectTitle = isset($view->objectTitle) ? $view->objectTitle : '';
        $result->objectID = isset($view->objectID) ? (int)$view->objectID : 0;
        $result->canUpdateOrder = isset($view->canUpdateOrder) ? (int)$view->canUpdateOrder : 0;
        $result->libType = isset($view->libType) ? $view->libType : '';
        $result->spaceType = isset($view->spaceType) ? $view->spaceType : '';

        return $result;
    }
}