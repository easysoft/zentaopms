<?php
class docTest
{
    public function __construct($account = 'admin')
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('doc');
        $this->objectModel->config->global->syncProduct = '';

        su($account);

        $app->rawModule = 'doc';
        $app->rawMethod = 'index';
    }

    /**
     * 创建一个文档库。
     * Create a lib.
     *
     * @param  array        $param
     * @param  string       $type    api|project|product|execution|custom|mine
     * @param  string       $libType
     * @access public
     * @return object|array
     */
    public function createLibTest(array $param, string $type = '', string $libType = ''): object|array
    {
        $createFields = array('type' => '', 'name' => '', 'acl' => '', 'product' => 0, 'project' => 0, 'execution' => 0);

        $lib = new stdClass();
        foreach($createFields as $field => $defaultValue) $lib->{$field} = $defaultValue;
        foreach($param as $key => $value) $lib->{$key} = $value;
        $objectID = $this->objectModel->createLib($lib);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getLibById($objectID);
    }

    /**
     * 创建一个API文档库。
     * Creat a api doc library.
     *
     * @param  array              $param
     * @access public
     * @return array|object|false
     */
    public function createApiLibTest(array $param): array|object|bool
    {
        $this->objectModel->loadModel('api');

        $apiLib = new stdclass();
        $createFields = array('name' => '', 'baseUrl' => '', 'acl' => 'open', 'desc' => '测试详情', 'libType' => 'product', 'product' => 1, 'project' => 0);
        foreach($createFields as $field => $defaultValue) $apiLib->{$field} = $defaultValue;
        foreach($param as $key => $value) $apiLib->{$key} = $value;
        $objectID = $this->objectModel->createApiLib($apiLib);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getLibByID($objectID);
    }

    /**
     * 更新一个API接口库。
     * Update an api lib.
     *
     * @param  int        $id
     * @param  array      $param
     * @access public
     * @return array|bool
     */
    public function updateApiLibTest(int $id, array $param): array|bool
    {
        $this->objectModel->loadModel('api');

        $oldDoc = $this->objectModel->getLibByID($id);

        $data = new stdClass;
        foreach($param as $key => $value) $data->{$key} = $value;

        $changes = $this->objectModel->updateApiLib($id, $data);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 编辑一个文档库。
     * Update a lib.
     *
     * @param  int    $libID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function updateLibTest($libID, $param): array
    {
        $libData = new stdclass();
        foreach($param as $key => $value) $libData->{$key} = $value;
        $changes = $this->objectModel->updateLib($libID, $libData);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 通过类型获取文档列表数据。
     * Get doc list data by browse type.
     *
     * @param  string $browseType all|bySearch|openedbyme|editedbyme|byediteddate|collectedbyme
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getDocsByBrowseTypeTest(string $browseType, int $queryID, int $moduleID, string $sort): array
    {
        $docs = $this->objectModel->getDocsByBrowseType($browseType, $queryID, $moduleID, $sort);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 创建一个文档。
     * Create a doc.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function createTest(array $param): array
    {
        $labels = array();
        $createFields = array('lib' => 0, 'module' => 0, 'title' => '', 'keywords' => '', 'type' => 'text', 'content' => '', 'contentType' => 'html', 'acl' => 'private', 'status' => 'normal');

        $doc = new stdclass();
        foreach($createFields as $field => $defaultValue) $doc->{$field} = $defaultValue;
        foreach($param as $key => $value) $doc->{$key} = $value;
        $this->objectModel->create($doc, $labels);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_DOC)->where('title')->eq($doc->title)->andwhere('lib')->eq($doc->lib)->fetchAll('id');
    }

    /**
     * 批量插入独立的文档。
     * Insert seperate docs.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function insertSeperateDocsTest(array $param): array
    {
        $files = array();
        $createFields = array('lib' => 0, 'module' => 0, 'title' => '', 'keywords' => '', 'type' => 'text', 'content' => '', 'contentType' => 'html', 'acl' => 'private', 'status' => 'normal');

        $doc = new stdclass();
        foreach($createFields as $field => $defaultValue) $doc->{$field} = $defaultValue;
        foreach($param as $key => $value)
        {
            if($key == 'title')
            {
                $files[0]['title'] = $value;
                $files[0]['size']  = 0;
            }
            $doc->{$key} = $value;
        }

        $docContent          = new stdclass();
        $docContent->title   = $doc->title;
        $docContent->content = '';
        $docContent->type    = $doc->contentType;
        $docContent->digest  = '';
        $docContent->version = 1;

        unset($doc->contentType);
        $this->objectModel->insertSeperateDocs($doc, $docContent, $files);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_DOC)->where('title')->eq($doc->title)->andwhere('lib')->eq($doc->lib)->fetchAll('id');
    }

    /**
     * 创建独立的文档。
     * Create a seperate docs.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function createSeperateDocsTest(array $param): array
    {
        $createFields = array('lib' => 0, 'module' => 0, 'title' => '', 'keywords' => '', 'type' => 'text', 'content' => '', 'contentType' => 'html', 'acl' => 'private', 'status' => 'normal');

        $doc = new stdclass();
        foreach($createFields as $field => $defaultValue) $doc->{$field} = $defaultValue;
        foreach($param as $key => $value) $doc->{$key} = $value;

        $_FILES['files']['error']    = 0;
        $_FILES['files']['name']     = $doc->title;
        $_FILES['files']['size']     = 0;
        $_FILES['files']['tmp_name'] = 'txt';
        $this->objectModel->createSeperateDocs($doc);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_DOC)->where('title')->eq($doc->title)->andwhere('lib')->eq($doc->lib)->fetchAll('id');
    }

    /**
     * 编辑一个文档。
     * Update a doc.
     *
     * @param  int   $docID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateTest(int $docID, array $param): array
    {
        $createFields = array('lib' => 0, 'module' => 0, 'title' => '', 'keywords' => '', 'type' => 'text', 'content' => '', 'contentType' => 'html', 'acl' => 'private', 'status' => 'normal');

        $doc = new stdclass();
        foreach($createFields as $field => $defaultValue) $doc->{$field} = $defaultValue;
        foreach($param as $key => $value) $doc->{$key} = $value;

        $objects = $this->objectModel->update($docID, $doc);

        if(dao::isError()) return dao::getError();
        return $objects['changes'];
    }

    /**
     * 为更新文档处理数据。
     * Process data for update a doc.
     *
     * @param  int    $docID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function processDocForUpdateTest(int $docID, array $param): object
    {
        $createFields = array('lib' => 0, 'module' => 0, 'title' => '', 'keywords' => '', 'type' => 'text', 'content' => '', 'contentType' => 'html', 'acl' => 'private', 'status' => 'normal');

        $doc = new stdclass();
        foreach($createFields as $field => $defaultValue) $doc->{$field} = $defaultValue;
        foreach($param as $key => $value) $doc->{$key} = $value;

        $oldDoc = $this->objectModel->getByID($docID);
        $data   = $this->objectModel->processDocForUpdate($oldDoc, $doc);

        if(dao::isError()) return dao::getError();
        return $data[0];
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
     * 获取文档库的附件。
     * Get lib files.
     *
     * @param  string      $type        product|project|execution
     * @param  int         $objectID
     * @param  string|bool $searchTitle
     * @access public
     * @return array
     */
    public function getLibFilesTest(string $type, int $objectID, string|bool $searchTitle = false): array
    {
        $files = $this->objectModel->getLibFiles($type, $objectID, $searchTitle);
        if(dao::isError()) return dao::getError();

        return $files;
    }

    /**
     * 获取附件的来源。
     * Get file source pairs.
     *
     * @access public
     * @return array
     */
    public function getFileSourcePairsTest(): array
    {
        $files = $this->objectModel->dao->select('*')->from(TABLE_FILE)->fetchAll();
        $files = $this->objectModel->getFileSourcePairs($files);

        if(dao::isError()) return dao::getError();
        return $files;
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

    /**
     * 设置文档的导航。
     * Set doc menu by type.
     *
     * @param  string $type      mine|project|execution|product|custom
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function setMenuByTypeTest(string $type, int $objectID, int $libID, int $appendLib = 0): array
    {
        $objects = $this->objectModel->setMenuByType($type, $objectID, $libID, $appendLib);
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

    /**
     * 构造搜索表单。
     * Build search form.
     *
     * @param  int    $libID
     * @param  array  $libIdList
     * @param  int    $queryID
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildSearchFormTest(int $libID, array $libIdList, int $queryID, string $type): array
    {
        $libs = $this->objectModel->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($libIdList)->fetchAll('id');
        $this->objectModel->buildSearchForm($libID, $libs, $queryID, '', $type);
        return $this->objectModel->config->doc->search;
    }

    /**
     * 通过搜索获取文档列表数据。
     * Get docs by search.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $libID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getDocsBySearchTest(string $type, int $objectID, int $libID, int $queryID): array
    {
        $docs = $this->objectModel->getDocsBySearch($type, $objectID, $libID, $queryID);
        if(dao::isError()) return dao::getError();

        return $docs;
    }

    /**
     * 构造搜索条件。
     * Build search query.
     *
     * @param  string $type
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function buildQueryTest(string $type, int $queryID): string
    {
        return $this->objectModel->buildQuery($type, $queryID);
    }

    /**
     * 通过对象ID获取文档库。
     * Get libs by object.
     *
     * @param  string $type
     * @param  int    $objectID
     * @param  int    $appendLib
     * @access public
     * @return array
     */
    public function getLibsByObjectTest(string $type, int $objectID, int $appendLib = 0): array
    {
        $libs = $this->objectModel->getLibsByObject($type, $objectID, $appendLib);

        if(dao::isError()) return dao::getError();
        return $libs;
    }

    /**
     * 检查是否有权限访问文档库。
     * Check priv for lib.
     *
     * @param  string $type     lib|doc
     * @param  int    $objectID
     * @param  string $extra    notdoc
     * @access public
     * @return bool
     */
    public function checkPrivLibTest(string $type, int $objectID, string $extra = ''): bool
    {
        $object = $type == 'lib' ? $this->objectModel->getLibByID($objectID) : $this->objectModel->getByID($objectID);
        return $this->objectModel->checkPrivLib($object, $extra);
    }

    /**
     * 获取有权限访问的文档库。
     * Get grant libs by doc.
     *
     * @access public
     * @return array
     */
    public function getPrivLibsByDocTest(): array
    {
        return $this->objectModel->getPrivLibsByDoc();
    }

    /**
     * 通过ID获取文档库信息。
     * Get library by id.
     *
     * @param  int          $libID
     * @access public
     * @return object|false
     */
    public function getLibByIdTest(int $libID): object|bool
    {
        return $this->objectModel->getLibByID($libID);
    }

    /**
     * 获取有权限查看的文档ID列表。
     * Get grant doc id list.
     *
     * @param  array $libIdList
     * @param  int    $moduleID
     * @param  string $mode     all|normal|children
     * @access public
     * @return string
     */
    public function getPrivDocsTest(array $libIdList = array(), int $moduleID = 0, string $mode = 'normal'): string
    {
        $docs = $this->objectModel->getPrivDocs($libIdList, $moduleID, $mode);

        if(dao::isError()) return dao::getError();
        return implode(',', $docs);
    }

    /**
     * 处理文档的收藏者信息。
     * Process collector to account.
     *
     * @param  array  $docIdList
     * @access public
     * @return array
     */
    public function processCollectorTest(array $docIdList): array
    {
        $docs = array();
        if(!empty($docIdList)) $docs = $this->objectModel->getByIdList($docIdList);

        $docs = $this->objectModel->processCollector($docs);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取当前文档库下的文档列表数据。
     * Get doc list by libID.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  string $browseType all|draft
     * @access public
     * @return array
     */
    public function getDocsTest(int $libID, int $moduleID, string $browseType): array
    {
        $docs = $this->objectModel->getDocs($libID, $moduleID, $browseType, 'id_desc');

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取编辑过的文档ID列表。
     * Get the list of doc id list that have been edited.
     *
     * @access public
     * @return string|array
     */
    public function getEditedDocIdListTest(): string|array
    {
        $docIdList = $this->objectModel->getEditedDocIdList();

        if(dao::isError()) return dao::getError();
        return implode(';', $docIdList);
    }

    /**
     * 获取我的空间下的文档列表数据。
     * Get doc list under the my space.
     *
     * @param  string $type       view|collect|createdby|editedby
     * @param  string $browseType all|draft|bysearch
     * @param  string $query
     * @access public
     * @return array
     */
    public function getMySpaceDocsTest(string $type, string $browseType, string $query = ''): array
    {
        $docs = $this->objectModel->getMySpaceDocs($type, $browseType, $query);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取我的空间下的文档列表数据。
     * Get doc list under the my space.
     *
     * @param  string $type       view|collect|createdby|editedby
     * @param  string $browseType all|draft|bysearch
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getMineListTest(string $type, string $browseType, int $queryID = 0): array
    {
        $docs = $this->objectModel->getMineList($type, $browseType, $queryID);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 构建文档库树形结构的节点。
     * Build a node of the tree structure of the document library.
     *
     * @param  int    $libID
     * @param  int    $id
     * @param  string $type     mine|product|project|execution|api|custom
     * @param  int    $moduleID
     * @param  int    $objectID
     * @param  int    $showDoc
     * @access public
     * @return object
     */
    public function buildLibItemTest(int $libID, int $id, string $type, int $moduleID, int $objectID, int $showDoc): object
    {
        $this->objectModel->loadModel('setting')->setItem('admin.doc.showDoc', $showDoc);
        $lib = $this->objectModel->getLibByID($id);

        $item = $this->objectModel->buildLibItem($libID, $lib, $type, $moduleID, $objectID);

        if(dao::isError()) return dao::getError();
        return $item;
    }

    /**
     * 获取产品、项目、执行文档库的树形结构。
     * Get a tree structure of the product, project, and execution document library.
     *
     * @param  int    $libID
     * @param  array  $libIdList
     * @param  string $type       mine|product|project|execution|api|custom
     * @param  int    $moduleID
     * @param  int    $objectID
     * @param  string $browseType bysearch|byrelease
     * @param  int    $param
     * @access public
     * @return array
     */
    public function getObjectTreeTest(int $libID, array $libIdList, string $type, int $moduleID = 0, int $objectID = 0, string $browseType = '', int $param = 0): array
    {
        $libs = $this->objectModel->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($libIdList)->fetchAll('id');
        $data = $this->objectModel->getObjectTree($libID, $libs, $type, $moduleID, $objectID, $browseType, $param);

        if(dao::isError()) return dao::getError();
        return isset($data[0][$type]) ? $data[0][$type] : array();
    }

    /**
     * 处理产品、项目、执行的文档库树形结构。
     * Process the tree structure of the document library of product, project, and execution.
     *
     * @param  int          $libID
     * @param  string       $type     mine|product|project|execution|api|custom
     * @param  int          $objectID
     * @access public
     * @return array|object
     */
    public function processObjectTree(int $libID, string $type, int $objectID): array|object
    {
        $libTree = array($type => array());
        $data    =  $this->objectModel->processObjectTree($libTree, $type, $libID, $objectID);

        return empty($data[$type]) ? array() : current($data[$type]);
    }

    /**
     * 获取文档库的树形结构。
     * Get lib tree.
     *
     * @param  int    $libID
     * @param  array  $libIdList
     * @param  string $type       mine|product|project|execution|api|custom
     * @param  int    $moduleID
     * @param  int    $objectID
     * @param  string $browseType bysearch|byrelease
     * @param  int    $param
     * @access public
     * @return array
     */
    public function getLibTreeTest(int $libID, array $libIdList, string $type, int $moduleID = 0, int $objectID = 0, string $browseType = '', int $param = 0): array
    {
        $libs    = $this->objectModel->dao->select('*')->from(TABLE_DOCLIB)->where('id')->in($libIdList)->fetchAll('id');
        $libTree = $this->objectModel->getLibTree($libID, $libs, $type, $moduleID, $objectID, $browseType, $param);

        if(dao::isError()) return dao::getError();
        return $libTree;
    }

    /**
     * 删除附件。
     * Delete files.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function deleteFilesTest(array $idList): array
    {
        $this->objectModel->deleteFiles($idList);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('deleted')->from(TABLE_FILE)->where('id')->in($idList)->fetchPairs();
    }

    /**
     * 获取文档的所有操作信息。
     * Get action by doc ID.
     *
     * @param  int                $docID
     * @param  string             $action  view|collect
     * @param  string             $account
     * @access public
     * @return array|object|false
     */
    public function getActionByObjectTest(int $docID, string $action, string $account = ''): array|object|bool
    {
        $actions = $this->objectModel->getActionByObject($docID, $action, $account);

        if(dao::isError()) return dao::getError();
        return $actions;
    }

    /**
     * 删除一个动作。
     * Delete an action.
     *
     * @param  int    $actionID
     * @access public
     * @return bool
     */
    public function deleteActionTest(int $actionID): bool
    {
        return $this->objectModel->deleteAction($actionID);
    }

    /**
     * 创建一个操作。
     * Create an action.
     *
     * @param  int    $docID
     * @param  string $action  collect|view
     * @param  string $account
     * @access public
     * @return int|bool
     */
    public function createActionTest(int $docID, string $action, string $account = ''): int|bool
    {
        return $this->objectModel->createAction($docID, $action, $account);
    }

    /**
     * 获取文档库键值对。
     * Get doc liberary pairs.
     *
     * @param  string $type        all|includeDeleted|hasApi|product|project|execution|custom|mine
     * @param  string $extra
     * @param  int    $objectID
     * @param  string $excludeType product|project|execution|custom|mine
     * @access public
     * @return array
     */
    public function getLibPairsTest(string $type, string $extra = '', int $objectID = 0, string $excludeType = ''): array
    {
        $products   = $this->objectModel->loadModel('product')->getPairs();
        $projects   = $this->objectModel->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', 'kanban');
        $executions = $this->objectModel->loadModel('execution')->getPairs(0, 'sprint,stage', 'multiple,leaf');

        return $this->objectModel->getLibPairs($type, $extra, $objectID, $excludeType, $products, $projects, $executions);
    }

    /**
     * 获取文档库。
     * Get libraries.
     *
     * @param  string $type        all|includeDeleted|hasApi|product|project|execution|custom|mine
     * @param  string $extra       withObject|notdoc
     * @param  string $appendLibs
     * @param  int    $objectID
     * @param  string $excludeType product|project|execution|custom|mine
     * @access public
     * @return array
     */
    public function getLibsTest(string $type = '', string $extra = '', string $appendLibs = '', int $objectID = 0, string $excludeType = ''): array
    {
        return $this->objectModel->getLibs($type, $extra, $appendLibs, $objectID, $excludeType);
    }

    /**
     * 获取api文档库。
     * Get api libraries.
     *
     * @param  int    $appendLib
     * @param  string $objectType nolink|product|project
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getApiLibsTest(int $appendLib = 0, string $objectType = '', int $objectID = 0): array
    {
        return $this->objectModel->getApiLibs($appendLib, $objectType, $objectID);
    }

    /**
     * 获取带库名称的模块数据。
     * Get option menu for libs.
     *
     * @param  array  $libIdList
     * @param  string $docType   doc|api
     * @access public
     * @return array
     */
    public function getLibsOptionMenuTest(array $libIdList, string $docType): array
    {
        $libs = $this->objectModel->dao->select('id,name')->from(TABLE_DOCLIB)->where('id')->in($libIdList)->fetchPairs();
        return $this->objectModel->getLibsOptionMenu($libs, $docType);
    }

    /**
     * 处理文档数据。
     * Process doc data.
     *
     * @param  int          $docID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function processDocTest(int $docID, bool $setImgSize = false): object|bool
    {
        $doc = $this->objectModel->dao->select('*')->from(TABLE_DOC)->where('id')->eq($docID)->fetch();

        if(!$doc) return false;
        return $this->objectModel->processDoc($doc, 1, $setImgSize);
    }

    /**
     * 通过ID获取文档信息。
     * Get doc info by id.
     *
     * @param  int          $docID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByIDTest(int $docID, bool $setImgSize = false): object|bool
    {
        return $this->objectModel->getByID($docID, 1, $setImgSize);
    }

    /**
     * 获取已排序的产品数据。
     * Get ordered products.
     *
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedProductsTest(int $append): array
    {
        return $this->objectModel->getOrderedProducts($append);
    }

    /**
     * 获取已排序的项目数据。
     * Get ordered projects.
     *
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedProjectsTest(int $append): array
    {
        return $this->objectModel->getOrderedProjects($append);
    }

    /**
     * 获取已排序的执行数据。
     * Get ordered executions.
     *
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedExecutionsTest(int $append): array
    {
        return $this->objectModel->getOrderedExecutions($append);
    }

    /**
     * 获取已排序的对象数据。
     *  Get ordered objects for doc.
     *
     * @param  string $objectType
     * @param  string $returnType nomerge|merge
     * @param  int    $append
     * @access public
     * @return array
     */
    public function getOrderedObjectsTest(string $objectType = 'product', string $returnType = 'merge', int $append = 0): array
    {
        return $this->objectModel->getOrderedObjects($objectType, $returnType, $append);
    }

    /**
     * 通过ID获取产品/项目/执行的信息。
     * Get product/project/execution by ID.
     *
     * @param  string       $type
     * @param  int          $objectID
     * @access public
     * @return object|false
     */
    public function getObjectByIDTest(string $type, int $objectID): object|bool
    {
        return $this->objectModel->getObjectByID($type, $objectID);
    }

    /**
     * 获取关联产品的数据。
     * Get the data of the linked product.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getLinkedProductDataTest(int $productID): array
    {
        $data = $this->objectModel->getLinkedProductData($productID);

        if(dao::isError()) return dao::getError();
        return $data;
    }

    /**
     * 获取关联项目的数据。
     * Get the data of the linked project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getLinkedProjectDataTest(int $projectID, string $edition = ''): array
    {
        $this->objectModel->config->edition = $edition;
        $data = $this->objectModel->getLinkedProjectData($projectID);

        if(dao::isError()) return dao::getError();
        return $data;
    }

    /**
     * 获取关联执行的数据。
     * Get the data of the linked execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getLinkedExecutionDataTest(int $executionID): array
    {
        $data = $this->objectModel->getLinkedExecutionData($executionID);

        if(dao::isError()) return dao::getError();
        return $data;
    }

    /**
     * 获取关联产品/项目/执行的数据。
     * Get linked product/project/execution data.
     *
     * @param  string $type     product|project|execution
     * @param  int    $objectID
     * @param  string $edition
     * @access public
     * @return array
     */
    public function getLinkedObjectDataTest(string $type, int $objectID, string $edition = ''): array
    {
        $this->objectModel->config->edition = $edition;
        $data = $this->objectModel->getLinkedObjectData($type, $objectID);

        if(dao::isError()) return dao::getError();
        return $data;
    }

    /**
     * 获取文件图标。
     * Get file icon.
     *
     * @access public
     * @return array
     */
    public function getFileIconTest(): array
    {
        $files = $this->objectModel->dao->select('*')->from(TABLE_FILE)->fetchAll();
        return $this->objectModel->getFileIcon($files);
    }

    /**
     * 获取搜索后的文档列表。
     * Get doc list by search.
     *
     * @param  int    $queryID
     * @param  array  $hasPrivDocIdList
     * @param  array  $allLibIDList
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getMyDocListBySearchTest(int $queryID, array $hasPrivDocIdList, array $allLibIDList, string $sort): array
    {
        $docs = $this->objectModel->getMyDocListBySearch($queryID, $hasPrivDocIdList, $allLibIDList, $sort);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取我创建的文档。
     * Get docs created by me.
     *
     * @param  array  $hasPrivDocIdList
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getOpenedDocsTest(array $hasPrivDocIdList, string $sort): array
    {
        $docs = $this->objectModel->getOpenedDocs($hasPrivDocIdList, $sort);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取我编辑过的文档。
     * Get the docs that I have edited.
     *
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getEditedDocsTest(string $sort): array
    {
        $docs = $this->objectModel->getEditedDocs($sort);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取按照编辑时间倒序排序的文档。
     * Get the docs ordered by edited date.
     *
     * @param  array $hasPrivDocIdList
     * @param  array $allLibIDList
     * @access public
     * @return array
     */
    public function getOrderedDocsByEditedDateTest(array $hasPrivDocIdList, array $allLibIDList): array
    {
        $docs = $this->objectModel->getOrderedDocsByEditedDate($hasPrivDocIdList, $allLibIDList);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取我收藏的文档。
     * Get the docs that I have collected.
     *
     * @param  array  $hasPrivDocIdList
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getCollectedDocsTest(array $hasPrivDocIdList, string $sort): array
    {
        $docs = $this->objectModel->getCollectedDocs($hasPrivDocIdList, $sort);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 替换查询语句中的all。
     * Replace all in query.
     *
     * @param  string       $query
     * @access public
     * @return string|array
     */
    public function getDocQueryTest(string $query): string|array
    {
        $query = $this->objectModel->getDocQuery($query);

        if(dao::isError()) return dao::getError();
        return $query;
    }

    /**
     * 通过文档ID获取文档所属产品、项目、执行。
     * Get projects, executions and products by docIdList.
     *
     * @param  array  $docIdList
     * @access public
     * @return array
     */
    public function getObjectsByDocTest(array $docIdList): array
    {
        $data = $this->objectModel->getObjectsByDoc($docIdList);

        if(dao::isError()) return dao::getError();
        return $data;
    }

    /**
     * 通过ID列表获取文档信息。
     * Get docs info by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByIdListTest(array $idList): array
    {
        $docs = $this->objectModel->getByIdList($idList);

        if(dao::isError()) return dao::getError();
        return $docs;
    }

    /**
     * 获取执行文档库的所属模块的键值对。
     * Gets the key-value pair of the module by execution ID.
     *
     * @param  string $type normal|noData
     * @access public
     * @return array
     */
    public function getExecutionModulePairsTest(string $type): array
    {
        if($type == 'noData')
        {
            $this->objectModel->dao->delete()->from(TABLE_DOCLIB)->exec();
            $this->objectModel->dao->delete()->from(TABLE_MODULE)->exec();
        }

        $modulePairs = $this->objectModel->getExecutionModulePairs();

        if(dao::isError()) return dao::getError();
        return $modulePairs;
    }

    /**
     * 检查文档权限。
     * Check privilege for the document.
     *
     * @param  string $account
     * @param  int    $docID
     * @access public
     * @return bool
     */
    public function checkPrivDocTest(string $account = 'admin', int $docID = 0): bool
    {
        if($account != $this->objectModel->app->user->account) su($account);

        if($docID) $doc = $this->objectModel->dao->select('*')->from(TABLE_DOC)->where('id')->eq($docID)->fetch();
        if(!$docID) $doc = new stdclass();

        return $this->objectModel->checkPrivDoc($doc);
    }
}
