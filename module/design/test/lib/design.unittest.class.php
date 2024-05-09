<?php
class designTest
{
    /**
     * 构造函数。
     *
     * @param  string $account
     * @access public
     * @return void
     */
    public function __construct(string $account = 'admin')
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('design');

        su($account);

        $app->rawModule = 'design';
        $app->rawMethod = 'index';
        $app->setModuleName('design');
        $app->setMethodName('index');
    }

    /**
     * 创建一个设计。
     * Create a design.
     *
     * @param  array        $param
     * @access public
     * @return object|array
     */
    public function createTest(array $param = array()): object|array
    {
        $designData   = new stdClass();
        $createFields = array('project' => 11, 'desc' => '', 'version' => 1, 'createdBy' => $this->objectModel->app->user->account, 'createdDate' => helper::now());
        foreach($createFields as $field => $defaultValue) $designData->{$field} = $defaultValue;
        foreach($param as $key => $value) $designData->{$key} = $value;

        $objectID = $this->objectModel->create($designData);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($objectID);
    }

    /**
     * 批量创建设计。
     * Batch create designs.
     *
     * @param  array $param
     * @access public
     * @return array
     */
    public function batchCreateTest(array $dataList = array()): array
    {
        $designs = array();
        foreach($dataList as $data)
        {
            $design = new stdClass();
            foreach($data as $key => $value) $design->{$key} = $value;
            if(!isset($design->story)) $design->story = 0;

            $designs[] = $design;
        }

        $this->objectModel->dao->delete()->from(TABLE_DESIGN)->exec();
        $this->objectModel->batchCreate(11, 1, $designs);

        if(dao::isError()) return current(dao::getError());
        return $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('project')->eq(11)->andwhere('product')->eq(1)->fetchAll();
    }

    /**
     * 编辑一个设计。
     * Update a design.
     *
     * @param  int       $designID
     * @param  array     $data
     * @access public
     * @return array|bool
     */
    public function updateTest(int $designID, array $data = array()): array|bool
    {
        $oldDesign = $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        $fields    = array('product', 'name', 'story', 'desc', 'type');

        $design = new stdClass();
        $design->editedBy = $this->objectModel->app->user->account;
        $design->editedDate = helper::now();
        if($oldDesign)
        {
            foreach($fields as $field) $design->{$field} = isset($data[$field]) ? $data[$field] : $oldDesign->{$field};
        }

        $changes = $this->objectModel->update($designID, $design);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 更新设计的指派人。
     * Update assign of design.
     *
     * @param  int        $designID
     * @param  string     $assignTo
     * @access public
     * @return array|bool
     */
    public function assignTest(int $designID, string $assignTo = ''): array|bool
    {
        $design = new stdclass();
        $design->assignedTo = $assignTo;

        $changes = $this->objectModel->assign($designID, $design);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 设计关联代码提交。
     * Design link commits.
     *
     * @param  int          $designID
     * @param  int          $repoID
     * @param  array        $revisions
     * @access public
     * @return array|string
     */
    public function linkCommitTest(int $designID, int $repoID, array $revisions = array()): array|string
    {
        if($revisions) $this->objectModel->session->designRevisions = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->in($revisions)->fetchAll();

        $this->objectModel->linkCommit($designID, $repoID, $revisions);

        if(dao::isError()) return dao::getError();

        $commit = '';
        $design = $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!empty($design->commit)) $commit = str_replace(',', ';', $design->commit);
        return $commit;
    }

    /**
     * 设计解除代码提交关联。
     * Design unlink a commit.
     *
     * @param  int    $designID
     * @param  int    $commitID
     * @access public
     * @return array
     */
    public function unlinkCommitTest($designID = 0, $commitID = 0): array
    {
        $this->objectModel->unlinkCommit($designID, $commitID);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->where('AType')->eq('design')->andWhere('AID')->eq($designID)->fetchAll();
    }

    /**
     * 获取设计关联的代码提交。
     * Get commit.
     *
     * @param  int          $designID
     * @param  int          $recPerPage
     * @param  int          $pageID
     * @access public
     * @return array|string
     */
    public function getCommitTest($designID = 0, int $recPerPage = 20, int $pageID = 1): array|string
    {
        $this->objectModel->app->loadClass('pager', true);
        $pager  = pager::init(0, $recPerPage, $pageID);
        $design = $this->objectModel->getCommit($designID, $pager);

        if(dao::isError()) return dao::getError();

        $commits = '';
        if(!empty($design->commit))
        {
            foreach($design->commit as $commit)
            {
                $commits .= $commit->id . ';';
            }
        }
        return $commits;
    }

    /**
     * 获取搜索后的设计列表数据。
     * Get designs by search.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $queryID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getBySearchTest(int $projectID = 0, int $productID = 0, int $queryID = 0, string $orderBy = 'id_desc'): array
    {
        $designs = $this->objectModel->getBySearch($projectID, $productID, $queryID, $orderBy);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 获取设计列表数据。
     * Get design list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  int    $param
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getListTest(int $projectID = 0, int $productID = 0, string $type = 'all', int $param = 0, string $orderBy = 'id_desc'): array
    {
        $designs = $this->objectModel->getList($projectID, $productID, $type, $param, $orderBy);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 通过ID获取设计信息。
     * Get design information by ID.
     *
     * @param  int               $designID
     * @access public
     * @return object|bool|array
     */
    public function getByIDTest(int $id): object|bool|array
    {
        $design = $this->objectModel->getByID($id);

        if(dao::isError()) return dao::getError();
        return $design;
    }

    /**
     * 更新设计关联的代码提交记录。
     * Update the commit logs linked with the design.
     *
     * @param  int   $designID
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return array
     */
    public function updateLinkedCommitsTest(int $designID, int $repoID, array $revisions = array()): array
    {
        $this->objectModel->dao->delete()->from(TABLE_RELATION)->exec();
        $this->objectModel->updateLinkedCommits($designID, $repoID, $revisions);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->fetchAll();
    }

    /**
     * 通过ID获取提交记录。
     * Get commit by ID.
     *
     * @param  int               $revisionID
     * @access public
     * @return object|bool|array
     */
    public function getCommitByIDTest(int $revisionID = 0): object|bool|array
    {
        $commit = $this->objectModel->getCommitByID($revisionID);

        if(dao::isError()) return dao::getError();
        return $commit;
    }

    /**
     * 获取设计 id=>value 的键值对数组。
     * Get design id=>value pairs.
     *
     * @param  int    $productID
     * @param  string $type      all|HLDS|DDS|DBDS|ADS
     * @access public
     * @return array
     */
    public function getPairsTest(int $productID = 0, string $type = 'all'): array
    {
        $designs = $this->objectModel->getPairs($productID, $type);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 获取设计变更后受影响的任务。
     * Get affected tasks after design changed.
     *
     * @param  int    $designID
     * @access public
     * @return array
     */
    public function getAffectedScopeTest(int $designID = 0): array
    {
        $design = $this->objectModel->getByID($designID);
        if(!$design) $design = new stdclass();

        $design = $this->objectModel->getAffectedScope($design);
        return isset($design->tasks) ? $design->tasks : array();
    }
}
