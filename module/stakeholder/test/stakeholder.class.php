<?php
class stakeholderTest
{
    public function __construct(string $account = 'admin')
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('stakeholder');

        su($account);

        $app->rawModule = 'stakeholder';
        $app->rawMethod = 'browse';
        $app->setModuleName('stakeholder');
        $app->setMethodName('browse');
    }

    /**
     * Function create test by stakeholder
     *
     * @param  int   $objectID
     * @param  array $param
     * @access public
     * @return array
     */
    public function createTest($objectID, $param = array())
    {
        $createFields = array('from' => '', 'key' => '', 'user' => '', 'name' => '', 'phone' => '', 'qq' => '', 'weixin' => '',
            'email' => '', 'company' => '', 'nature' => '', 'analysis' => '', 'strategy' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create($objectID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($objectID);

        return $objects;
    }

    /**
     * Function batchCreate test by stakeholder
     *
     * @param  int   $projectID
     * @param  array $param
     * @access public
     * @return array
     */
    public function batchCreateTest($projectID, $param = array())
    {
        $realnames = array();
        $accounts  = array();

        $createFields = array('realnames' => $realnames, 'accounts' => $accounts);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->batchCreate($projectID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function edit test by stakeholder
     *
     * @param  int   $stakeholderID
     * @param  array $param
     * @access public
     * @return array
     */
    public function editTest($stakeholderID, $param = array())
    {
        $createFields = array('key' => '', 'nature' => '', 'analysis' => '', 'strategy' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->edit($stakeholderID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function communicate test by stakeholder
     *
     * @param  int $stakeholderID
     * @param  array $param
     * @access public
     * @return array
     */
    public function communicateTest($stakeholderID, $param = array())
    {
        global $tester;
        $createFields = array('comment' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->communicate($stakeholderID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($stakeholderID)->andwhere('objectType')->eq('stakeholder')->fetchAll();
        return $objects;
    }

    /**
     * 获取干系人列表数据。
     * Get stakeholder list.
     *
     * @param  int    $projectID
     * @param  string $browseType all|inside|outside|key
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getStakeholdersTest(int $projectID, string $browseType, string $sort): array
    {
        $stakeholders = $this->objectModel->getStakeholders($projectID, $browseType, $sort);

        if(dao::isError()) return dao::getError();
        return $stakeholders;
    }

    /**
     * 更新/插入用户信息。
     * Update/insert user info.
     *
     * @param  array        $params
     * @access public
     * @return array|object
     */
    public function replaceUserInfoTest($params = array()): array|object
    {
        $defaultFields = array('from' => '', 'key' => 0, 'user' => '', 'name' => '', 'phone' => '', 'qq' => '', 'weixin' => '',
            'email' => '', 'company' => 0, 'nature' => '', 'analysis' => '', 'strategy' => '', 'newUser' => '', 'newCompany' => '', 'companyName' => '');

        $data = new stdclass();
        foreach($defaultFields as $field => $defaultValue) $data->{$field} = $defaultValue;
        foreach($params as $key => $value) $data->{$key} = $value;

        $account = $this->objectModel->replaceUserInfo($data);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_USER)->where('account')->eq($account)->fetch();
    }
}
