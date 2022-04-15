<?php
class treeTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('tree');
    }

    /**
     * Test get module by id.
     *
     * @param  int    $moduleID
     * @access public
     * @return object
     */
    public function getByIDTest($moduleID)
    {
        $object = $this->objectModel->getByID($moduleID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * get all module pairs with path.
     *
     * @param  string $type
     * @access public
     * @return int
     */
    public function getAllModulePairsTest($type = 'task')
    {
        $objects = $this->objectModel->getAllModulePairs($type);

        if(dao::isError()) return dao::getError();

        return count($objects);
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

    /**
     * Test create an option menu in html.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  int    $branch
     * @access public
     * @return int
     */
    public function getOptionMenuTest($rootID, $type = 'story', $startModule = 0, $branch = 0)
    {
        $objects = $this->objectModel->getOptionMenu($rootID, $type, $startModule, $branch);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * getModulePairsTest
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $showModule
     * @param  string $extra
     * @access public
     * @return int
     */
    public function getModulePairsTest($rootID, $viewType = 'story', $showModule = 'end', $extra = '')
    {
        $objects = $this->objectModel->getModulePairs($rootID, $viewType, $showModule, $extra);

        if(dao::isError()) return dao::getError();

        return count($objects);
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

    public function createStoryLinkTest($moduleID, $extra = array())
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createStoryLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(projectstory|execution|product).*(title='.*').*/", '$1 $2', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createLineLinkTest($moduleID, $extra)
    {
        $objects = $this->objectModel->createLineLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create task link.
     *
     * @param  int    $moduleID
     * @param  int    $extra
     * @access public
     * @return string
     */
    public function createTaskLinkTest($moduleID, $extra)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createTaskLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*task.(\d*).*byModule.(\d*).*(title='.*').*/", '$1 $2 $3', $link);
        $string = str_replace("\n", '', $string);
        return $string;
    }

    public function createProjectStoryLinkTest($type, $module, $extra)
    {
        $objects = $this->objectModel->createProjectStoryLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test create requirment link.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function createRequirementLinkTest($moduleID)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createBugLink($type, $module);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
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

    public function createTaskManageLinkTest($moduleID, $extra)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createTaskManageLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/((.*) <a.*tree.([a-z]*).*>([\x{4e00}-\x{9fa5}]*)<\/a>.*)|( <input .* \/>)/u", '$2 $3 $4', $link);
        $string = str_replace("\n", '', $string);
        return $string;
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

    /**
     * Create case link.
     *
     * @param  int    $moduleID
     * @param  array  $extra
     * @access public
     * @return string
     */
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

    /**
     * Test create test task link.
     *
     * @param  int    $moduleID
     * @param  int    $extra
     * @access public
     * @return string
     */
    public function createTestTaskLinkTest($moduleID, $extra)
    {
        $type   = '';
        $module = $this->objectModel->getByID($moduleID);

        $link = $this->objectModel->createTestTaskLink($type, $module, $extra);

        if(dao::isError()) return dao::getError();

        $string = preg_replace("/.*(title='.*').*/", '$1', $link);
        $string = str_replace("\n", '', $string);
        return $string;
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

    /**
     * Test get id list of a module's childs.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function getAllChildIdTest($moduleID)
    {
        $objects = $this->objectModel->getAllChildId($moduleID);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $objectID) $ids .= ',' . $objectID;
        return $ids;
    }

    public function getProjectModuleTest($projectID, $productID = 0)
    {
        $objects = $this->objectModel->getProjectModule($projectID, $productID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get parents of a module.
     *
     * @param  int    $moduleID
     * @access public
     * @return string
     */
    public function getParentsTest($moduleID)
    {
        $objects = $this->objectModel->getParents($moduleID);

        if(dao::isError()) return dao::getError();

        $ids = '';
        foreach($objects as $object) $ids .= ',' . $object->id;
        return $ids;
    }

    /**
     * Test get product by moduleID.
     *
     * @param  int $moduleID
     * @access public
     * @return object
     */
    public function getProductTest($moduleID)
    {
        $object = $this->objectModel->getProduct($moduleID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function getStoryModuleTest($moduleID)
    {
        $objects = $this->objectModel->getStoryModule($moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get modules name.
     *
     * @param  array  $moduleIdList
     * @param  bool    $allPath
     * @param  bool    $branchPath
     * @access public
     * @return array
     */
    public function getModulesNameTest($moduleIdList, $allPath = true, $branchPath = false)
    {
        $objects = $this->objectModel->getModulesName($moduleIdList, $allPath, $branchPath);

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

    /**
     * Test delete a module.
     *
     * @param  int    $moduleID
     * @param  object $null
     * @access public
     * @return object
     */
    public function deleteTest($moduleID, $null = null)
    {
        $this->objectModel->delete($moduleID, $null);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($moduleID);
        return $object;
    }

    /**
     * Test fix the path, grade fields according to the id and parent fields.
     *
     * @param  int    $root
     * @param  string $type
     * @access public
     * @return object
     */
    public function fixModulePathTest($root, $type)
    {
        $this->objectModel->fixModulePath($root, $type);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_MODULE)->where('root')->eq($root)->andWhere('type')->eq($type)->andWhere('deleted')->eq(0)->fetchAll('id');
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

    /**
     * Test get full task tree.
     *
     * @param  int    $root
     * @param  string $viewType
     * @param  array $keepModules
     * @access public
     * @return string
     */
    public function getDataStructureTest($root, $viewType, $keepModules = array())
    {
        global $tester;

        $stmt = $tester->dbh->query($this->objectModel->buildMenuQuery($root, $viewType));

        $objects = $this->objectModel->getDataStructure($stmt, $viewType, $keepModules);

        if(dao::isError()) return dao::getError();

        $child = '';
        foreach($objects as $object) $child .= isset($object->children) ? "$object->id:" . count($object->children) . ';' : "$object->id:0;";
        return $child;
    }

    public function getDocStructureTest()
    {
        $objects = $this->objectModel->getDocStructure();

        if(dao::isError()) return dao::getError();

        $child = '';
        foreach($objects as $object) $child .= isset($object[0]->children) ? $object[0]->id . ':' . count($object[0]->children) . ';' : "$object[0]->id:0;";
        return $child;
    }
}
