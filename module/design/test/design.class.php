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
     * Function update test by desgin
     *
     * @param  int   $designID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateTest($designID, $param = array())
    {
        global $tester;

        $labels = array();
        $files  = array();

        $createFields = $tester->dao->select('`product`,`story`,`type`,`name`,`desc`')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetchAll();
        $createFields[0]->labels = $labels;
        $createFields[0]->files  = $files;
        foreach($createFields[0] as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($designID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function assign test by desgin
     *
     * @param  int $designID
     * @param  array $param
     * @access public
     * @return array
     */
    public function assignTest($designID, $param = array())
    {
        global $tester;

        $createFields = array('assignedTo' => '', 'comment' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->assign($designID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetchAll();

        return $objects;
    }

    public function linkCommitTest($designID, $repoID, $param = array())
    {
        global $tester;

        $revision = array();

        $createFields = array('revision' => $revision);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->linkCommit($designID, $repoID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetchAll();

        return $objects;
    }

    public function unlinkCommitTest($designID = 0, $commitID = 0)
    {
        $objects = $this->objectModel->unlinkCommit($designID = 0, $commitID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCommitTest($designID = 0, $pager = null)
    {
        $objects = $this->objectModel->getCommit($designID = 0, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
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
}
