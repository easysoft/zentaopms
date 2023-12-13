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
     * Function create test by design
     *
     * @param  int   $projectID
     * @param  array $param
     * @access public
     * @return object
     */
    public function createTest($projectID, $param = array())
    {
        $labels = array();
        $files  = array();

        $createFields = array('product' => '', 'story' => '', 'type' => '', 'name' => '', 'labels' => $labels, 'files' => $files, 'desc' => '');
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create($projectID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($objectID);

        return $objects;
    }

    /**
     * Function batchCreate test by desgin
     *
     * @param  int   $projectID
     * @param  int   $productID
     * @param  array $param
     * @access public
     * @return array
     */
    public function batchCreateTest($projectID, $productID, $param = array())
    {
        global $tester;

        $story = array('', '', '', '');
        $type  = array('', '', '', '');
        $name  = array('', '', '', '');
        $desc  = array('', '', '', '');

        $createFields = array('story' => $story, 'type' => $type, 'name' => $name, 'desc' => $desc);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->batchCreate($projectID, $productID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_DESIGN)->where('project')->eq($projectID)->andwhere('product')->eq($productID)->fetchAll();

        return $objects;
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
}
