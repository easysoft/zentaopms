<?php
class docTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('doc');
    }

    /**
     * Function createLib test by doc
     *
     * @param  array $param
     * @access public
     * @return object
     */
    public function createLibTest($param)
    {
        $createFields = array('type' => '', 'name' => '', 'acl' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $objectID = $this->objectModel->createLib();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getLibById($objectID);

        return $objects;
    }

    /**
     * Function createApiLib test by doc
     *
     * @param  array $param
     * @access public
     * @return object
     */
    public function createApiLibTest($param)
    {
        global $tester;
        $tester->loadModel('api');
        $tester->app->loadLang('doclib');

        $createFields = array('name' => '', 'baseUrl' => '', 'acl' => '', 'desc' => '测试详情');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;
        $objectID = $this->objectModel->createApiLib();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getLibById($objectID);

        return $objects;
    }

    /**
     * Function updateApiLib test by doc
     *
     * @param  int $id
     * @param  array $param
     * @access public
     * @return void
     */
    public function updateApiLibTest($id, $param)
    {
        global $tester;
        $tester->app->loadConfig('api');
        $tester->app->loadLang('doclib');

        $oldDoc = $this->objectModel->getLibById($id);
        $data = new stdClass;
        foreach($param as $key => $value) $data->$key = $value;

        $objects = $this->objectModel->updateApiLib($id, $oldDoc, $data);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function updateLib test by doc
     *
     * @param  int $libID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateLibTest($libID, $param)
    {
        global $tester;
        $tester->app->loadConfig('doc');

        foreach($param as $key => $value) $_POST[$key] = $value;
        $objects = $this->objectModel->updateLib($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function getDocsByBrowseType test by doc
     *
     * @param  string $browseType
     * @param  array  $moduleID
     * @param  string $sort
     * @param  mixed $pager
     * @access public
     * @return array
     */
    public function getDocsByBrowseTypeTest($browseType, $moduleID, $sort = 'id_desc', $pager = null)
    {
        global $tester;
        $tester->app->loadConfig('doc');

        $objects = $this->objectModel->getDocsByBrowseType($browseType, $queryID = '', $moduleID, $sort, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function create test by doc
     *
     * @param  array $param
     * @access public
     * @return array
     */
    public function createTest($param)
    {
        global $tester;
        $tester->loadModel('api');
        $tester->app->loadLang('doclib');

        $labels = array();
        $files  = array();

        $createFields = array('lib' => '', 'module' => '', 'title' => '', 'keywords' => '', 'type' => '', 'content' => '', 'contentMarkdown' => '', 'contentType' => '',
        'url' => '', 'labels' => $labels, 'files' => $files, 'contactListMenu' => '', 'acl' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->create();

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_DOC)->where('title')->eq($_POST['title'])->andwhere('lib')->eq($_POST['lib'])->fetchAll();
        unset($_POST);

        return $objects;
    }

    /**
     * Function update test by doc
     *
     * @param  int $docID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateTest($docID, $param)
    {
        global $tester;
        $tester->app->loadConfig('api');
        $tester->app->loadLang('doclib');

        $labels = array();
        $files  = array();

        $createFields = array('lib' => '', 'module' => '', 'title' => '', 'keywords' => '', 'type' => '', 'content' => '', 'contentType' => '',
        'url' => '', 'labels' => $labels, 'files' => $files, 'contactListMenu' => '', 'acl' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($docID);

        if(dao::isError()) return dao::getError();

        return $objects["changes"];
    }

    /**
     * Function saveDraft test by doc
     *
     * @param mixed $docID
     * @param array $param
     * @access public
     * @return void
     */
    public function saveDraftTest($docID, $param = array())
    {
        global $tester;
        $tester->app->loadConfig('doc');
        $tester->app->loadConfig('allowedTags');
        $createFields = array('content' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->saveDraft($docID);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('id,draft')->from(TABLE_DOC)->where('id')->eq($docID)->fetchAll('id');
        unset($_POST);

        return $objects;
    }

    /**
     * Function getAllLibsByType test by doc
     *
     * @param mixed $type
     * @param mixed $product
     * @param mixed $pager
     * @access public
     * @return void
     */
    public function getAllLibsByTypeTest($type, $product, $pager = null)
    {
        $objects = $this->objectModel->getAllLibsByType($type, $pager, $product);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test stat module and document counts of lib.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function statLibCountsTest($idList)
    {
        $objects = $this->objectModel->statLibCounts($idList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get lib files.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return string
     */
    public function getLibFilesTest($type, $objectID, $orderBy, $pager = null)
    {
        $objects = $this->objectModel->getLibFiles($type, $objectID, $orderBy, $pager = null);

        if(dao::isError()) return dao::getError();

        $titles = '';
        foreach($objects as $object)
        {
            $titles .= "$object->title,";
        }
        $titles = trim($titles, ',');
        return $titles;
    }

    /**
     * Test get file source pairs.
     *
     * @param  array $files
     * @access public
     * @return void
     */
    public function getFileSourcePairsTest($type, $objectID)
    {
        $files = $this->objectModel->getLibFiles($type, $objectID, 't1.id_desc', $pager = null);

        $objects = $this->objectModel->getFileSourcePairs($files);

        if(dao::isError()) return dao::getError();

        $counts = '';
        foreach($objects as $type => $items)
        {
            $counts .= "$type:" . count($items) .';';
        }
        return $counts;
    }

    /**
     * Test get file icon.
     *
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function getFileIconTest($type, $objectID)
    {
        $files = $this->objectModel->getLibFiles($type, $objectID, 't1.id_desc', $pager = null);

        $objects = $this->objectModel->getFileIcon($files);

        if(dao::isError()) return dao::getError();

        $icons = '';
        foreach($objects as $object)
        {
            preg_match("/icon-[^']*/", $object, $matches);
            $icons .= "$matches[0] ";
        }
        $icons = trim($icons);
        return $icons;
    }

    /**
     * Test get doc tree.
     *
     * @param  int $libID
     * @access public
     * @return string
     */
    public function getDocTreeTest($libID)
    {
        $objects = $this->objectModel->getDocTree($libID);

        if(dao::isError()) return dao::getError();

        $names = '';
        foreach($objects as $object)
        {
            $names .= "$object->name:";
            foreach($object->children as $children) $names .= (isset($children->name) ? $children->name : $children->title) . ",";
            $names  = trim($names, ',');
            $names .= ";";
        }
        return $names;
    }

    /**
     * Test fill docs in tree.
     *
     * @param  object $node
     * @param  int    $libID
     * @access public
     * @return object
     */
    public function fillDocsInTreeTest($node, $libID)
    {
        $object = $this->objectModel->fillDocsInTree($node, $libID);

        if(dao::isError()) return dao::getError();

        $docsCounts = "$object->name:$object->docsCount;";
        foreach($object->children as $children)
        {
            if(isset($children->type) and $children->type == 'doc') continue;
            $docsCounts .= "$children->name:$children->docsCount;";
        }
        return $docsCounts;
    }

    public function getProductCrumbTest($productID, $executionID = 0)
    {
        $objects = $this->objectModel->getProductCrumb($productID, $executionID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test set lib users.
     *
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function setLibUsersTest($type, $objectID)
    {
        $objects = $this->objectModel->setLibUsers($type, $objectID);

        if(dao::isError()) return dao::getError();

        return implode($objects, ',');
    }

    public function getLibIdListByProjectTest($projectID = 0)
    {
        $objects = $this->objectModel->getLibIdListByProject($projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get statistic information.
     *
     * @access public
     * @return object
     */
    public function getStatisticInfoTest()
    {
        $objects = $this->objectModel->getStatisticInfo();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get the previous and next doc.
     *
     * @param  int $docID
     * @param  int $libID
     * @access public
     * @return object
     */
    public function getPreAndNextDocTest($docID, $libID)
    {
        $objects = $this->objectModel->getPreAndNextDoc($docID, $libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printChildModuleTest($module, $libID, $methodName, $browseType, $moduleID)
    {
        $objects = $this->objectModel->printChildModule($module, $libID, $methodName, $browseType, $moduleID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildCrumbTitleTest($libID = 0, $param = 0, $title = '')
    {
        $objects = $this->objectModel->buildCrumbTitle($libID = 0, $param = 0, $title = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildCreateButton4DocTest($objectType, $objectID, $libID)
    {
        $objects = $this->objectModel->buildCreateButton4Doc($objectType, $objectID, $libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildCollectButton4DocTest()
    {
        $objects = $this->objectModel->buildCollectButton4Doc();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildBrowseSwitchTest($type, $objectID, $viewType)
    {
        $objects = $this->objectModel->buildBrowseSwitch($type, $objectID, $viewType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setFastMenuTest($fastLib)
    {
        $objects = $this->objectModel->setFastMenu($fastLib);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get toList and ccList.
     *
     * @param  int    $docID
     * @access public
     * @return bool|array
     */
    public function getToAndCcListTest($docID)
    {
        $doc     = $this->objectModel->getByID($docID);
        $objects = $this->objectModel->getToAndCcList($doc);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function selectTest($type, $objects, $objectID, $libs, $libID = 0)
    {
        $objects = $this->objectModel->select($type, $objects, $objectID, $libs, $libID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getApiModuleTreeTest($rootID, $docID = 0, $release = 0)
    {
        $objects = $this->objectModel->getApiModuleTree($rootID, $docID = 0, $release = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTreeMenuTest($type, $objectID, $rootID, $startModule = 0, $docID = 0)
    {
        $objects = $this->objectModel->getTreeMenu($type, $objectID, $rootID, $startModule = 0, $docID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function summaryTest($files)
    {
        $objects = $this->objectModel->summary($files);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setMenuByTypeTest($type, $objectID, $libID, $appendLib = 0)
    {
        $objects = $this->objectModel->setMenuByType($type, $objectID, $libID, $appendLib = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Whether the url of link type documents needs to be autoloaded.
     *
     * @param  int    $docID
     * @access public
     * @return bool
     */
    public function checkAutoloadPageTest($docID)
    {
        $doc     = $this->objectModel->getByID($docID);
        $objects = $this->objectModel->checkAutoloadPage($doc);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check api library name.
     *
     * @param  object $lib
     * @param  string $libType
     * @param  int    $libID
     * @access public
     * @return bool
     */
    public function checkApiLibNameTest($lib, $libType, $libID = 0)
    {
        $this->objectModel->checkApiLibName($lib, $libType, $libID);

        if(dao::isError())
        {
            $errors = dao::getError();
            return zget($errors, 'name', '1');
        }

        return 'noerror';
    }
}
