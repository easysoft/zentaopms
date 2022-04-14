<?php
class treeTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('tree');
    }

    public function getByIDTest($moduleID)
    {
        $objects = $this->objectModel->getByID($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getAllModulePairsTest($type = 'task')
    {
        $objects = $this->objectModel->getAllModulePairs($type = 'task');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test build the sql query.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  string $branch
     * @access public
     * @return string
     */
    public function buildMenuQueryTest($rootID, $type, $startModule = 0, $branch = 'all')
    {
        $string = $this->objectModel->buildMenuQuery($rootID, $type, $startModule, $branch);

        if(dao::isError()) return dao::getError();

        return $string;
    }

    public function getOptionMenuTest($rootID, $type = 'story', $startModule = 0, $branch = 0)
    {
        $objects = $this->objectModel->getOptionMenu($rootID, $type = 'story', $startModule = 0, $branch = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getModulePairsTest($rootID, $viewType = 'story', $showModule = 'end', $extra = '')
    {
        $objects = $this->objectModel->getModulePairs($rootID, $viewType = 'story', $showModule = 'end', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskOptionMenuTest($rootID, $productID = 0, $startModule = 0, $extra = '')
    {
        $objects = $this->objectModel->getTaskOptionMenu($rootID, $productID = 0, $startModule = 0, $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test build tree array.
     *
     * @param  array  & $&treeMenu
     * @param  array  $modules
     * @param  int    $moduleID
     * @param  string $moduleName
     * @access public
     * @return array
     */
    public function buildTreeArrayTest(& $treeMenu, $modules, $moduleID, $moduleName = '/')
    {
        $module     = $this->objectModel->getByID($moduleID);
        $moduleName = $module->name;

        $this->objectModel->buildTreeArray($treeMenu, $modules, $module, $moduleName);

        if(dao::isError()) return dao::getError();

        $objects = array();
        foreach($treeMenu as $id => $string) $objects[$id] = strlen($string);
        return $objects;
    }

    public function getTreeMenuTest($rootID, $type = 'root', $startModule = 0, $userFunc = '', $extra = '', $branch = 0, $extraParams = '')
    {
        $objects = $this->objectModel->getTreeMenu($rootID, $type = 'root', $startModule = 0, $userFunc = '', $extra = '', $branch = 0, $extraParams = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskTreeMenuTest($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
    {
        $objects = $this->objectModel->getTaskTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskStructureTest($rootID, $productID = 0)
    {
        $objects = $this->objectModel->getTaskStructure($rootID, $productID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTreeStructureTest($rootID, $type)
    {
        $objects = $this->objectModel->getTreeStructure($rootID, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBugTreeMenuTest($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
    {
        $objects = $this->objectModel->getBugTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCaseTreeMenuTest($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
    {
        $objects = $this->objectModel->getCaseTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectStoryTreeMenuTest($rootID, $startModule = 0, $userFunc = '')
    {
        $objects = $this->objectModel->getProjectStoryTreeMenu($rootID, $startModule = 0, $userFunc = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildTreeTest(& $treeMenu, $module, $type, $userFunc, $extra, $branch = 'all')
    {
        $objects = $this->objectModel->buildTree($treeMenu, $module, $type, $userFunc, $extra, $branch = 'all');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskTreeModulesTest($executionID, $parent = false, $linkObject = 'story')
    {
        $objects = $this->objectModel->getTaskTreeModules($executionID, $parent = false, $linkObject = 'story');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createStoryLinkTest($type, $module, $extra = array())
    {
        $objects = $this->objectModel->createStoryLink($type, $module, $extra = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createLineLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createLineLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTaskLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createTaskLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createProjectStoryLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createProjectStoryLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createRequirementLinkTest($type, $module)
    {
        $objects = $this->objectModel->createRequirementLink($type, $module);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createDocLinkTest($type, $module, $extra = '')
    {
        $objects = $this->objectModel->createDocLink($type, $module, $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createManageLinkTest($moduleID, $extra)
    {
        $type   = 0;
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createManageLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return substr($link, 0, 15);
    }

    public function createTaskManageLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createTaskManageLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test reate link of a bug.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function createBugLinkTest($moduleID)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createBugLink($type, $module);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createCaseLinkTest($moduleID, $extra = array('branchID' => 0))
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createCaseLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createTestTaskLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createTestTaskLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create case lib link.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function createCaseLibLinkTest($moduleID)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createCaseLibLink($type, $module);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createBranchLinkTest($type, $rootID, $branchID, $branch)
    {
        $objects = $this->objectModel->createBranchLink($type, $rootID, $branchID, $branch);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create feedback link.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function createFeedbackLinkTest($moduleID)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createFeedbackLink($type, $module);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createTrainSkillLinkTest($type, $module, $extra = '')
    {
        $objects = $this->objectModel->createTrainSkillLink($type, $module, $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTrainCourseLinkTest($type, $module, $extra = '')
    {
        $objects = $this->objectModel->createTrainCourseLink($type, $module, $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTrainPostLinkTest($type, $module, $extra = '')
    {
        $objects = $this->objectModel->createTrainPostLink($type, $module, $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSonsTest($rootID, $moduleID, $type = 'root', $branch = 0)
    {
        $objects = $this->objectModel->getSons($rootID, $moduleID, $type = 'root', $branch = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskSonsTest($rootID, $productID, $moduleID)
    {
        $objects = $this->objectModel->getTaskSons($rootID, $productID, $moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getAllChildIdTest($moduleID)
    {
        $objects = $this->objectModel->getAllChildId($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectModuleTest($projectID, $productID = 0)
    {
        $objects = $this->objectModel->getProjectModule($projectID, $productID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getParentsTest($moduleID)
    {
        $objects = $this->objectModel->getParents($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductTest($moduleID)
    {
        $objects = $this->objectModel->getProduct($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStoryModuleTest($moduleID)
    {
        $objects = $this->objectModel->getStoryModule($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getModulesNameTest($moduleIdList, $allPath = true, $branchPath = false)
    {
        $objects = $this->objectModel->getModulesName($moduleIdList, $allPath = true, $branchPath = false);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateOrderTest($orders)
    {
        $objects = $this->objectModel->updateOrder($orders);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function manageChildTest($rootID, $type)
    {
        $objects = $this->objectModel->manageChild($rootID, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($moduleID)
    {
        $objects = $this->objectModel->update($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test change root.
     *
     * @param  int    $moduleID
     * @param  int    $oldRoot
     * @param  int    $newRoot
     * @param  string $type
     * @access public
     * @return int
     */
    public function changeRootTest($moduleID, $oldRoot, $newRoot, $type)
    {
        $this->objectModel->changeRoot($moduleID, $oldRoot, $newRoot, $type);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_STORY)->where('module')->eq($oldRoot)->andWhere('deleted')->eq(0)->fetchAll();
        return $type == 'story' ? count($objects) : 0;
    }

    public function deleteTest($moduleID, $null = null)
    {
        $objects = $this->objectModel->delete($moduleID, $null = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function fixModulePathTest($root, $type)
    {
        $objects = $this->objectModel->fixModulePath($root, $type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check unique module name.
     *
     * @param  object $module
     * @param  array  $modules
     * @param  array  $branches
     * @access public
     * @return string
     */
    public function checkUniqueTest($module, $modules = array(), $branches = array())
    {
        global $tester;
        $modules = $tester->dao->select('id,name')->from(TABLE_MODULE)->where('id')->in($modules)->fetchPairs();

        $repeatName = $this->objectModel->checkUnique($module, $modules, $branches);

        if(dao::isError()) return dao::getError();

        return $repeatName;
    }

    public function isMergeModuleTest($rootID, $viewType)
    {
        $objects = $this->objectModel->isMergeModule($rootID, $viewType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductStructureTest($rootID, $viewType, $branchID = 'all', $currentModuleID = 0)
    {
        $objects = $this->objectModel->getProductStructure($rootID, $viewType, $branchID = 'all', $currentModuleID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataStructureTest($stmt, $viewType, $keepModules = array())
    {
        $objects = $this->objectModel->getDataStructure($stmt, $viewType, $keepModules = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDocStructureTest()
    {
        $objects = $this->objectModel->getDocStructure();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
