<?php
class stakeholderTest
{
    public function __construct(string $account = 'admin')
    {
        su($account);

        global $tester, $app;
        $this->objectModel = $tester->loadModel('stakeholder');

        $app->rawModule = 'stakeholder';
        $app->rawMethod = 'browse';
        $app->setModuleName('stakeholder');
        $app->setMethodName('browse');
    }

    /**
     * 创建一个干系人。
     * Create a stakeholder.
     *
     * @param  array        $params
     * @access public
     * @return array|object
     */
    public function createTest(array $params= array()): array|object
    {
        $defaultFields = array('from' => '', 'key' => 0, 'user' => '', 'name' => '', 'phone' => '', 'qq' => '', 'weixin' => '', 'objectType' => 'project', 'objectID' => 0,
            'email' => '', 'company' => 0, 'nature' => '', 'analysis' => '', 'strategy' => '', 'newUser' => '', 'newCompany' => '', 'companyName' => '', '');

        $data = new stdclass();
        foreach($defaultFields as $field => $defaultValue) $data->{$field} = $defaultValue;
        foreach($params as $key => $value) $data->{$key} = $value;

        $stakeholderID = $this->objectModel->create($data);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STAKEHOLDER)->where('id')->eq($stakeholderID)->fetch();
    }

    /**
     * 批量创建项目的干系人。
     * Batch create stakeholders for a project.
     *
     * @param  int    $projectID
     * @param  array  $account
     * @access public
     * @return array
     */
    public function batchCreateTest(int $projectID, array $accounts = array()): array
    {
        $stakeholderList = $this->objectModel->batchCreate($projectID, $accounts);

        if(dao::isError()) return dao::getError();
        return $stakeholderList;
    }

    /**
     * 编辑一个干系人。
     * Edit a stakeholder.
     *
     * @param  int        $stakeholderID
     * @param  array      $params
     * @access public
     * @return array|bool
     */
    public function editTest($stakeholderID, $params = array()): array|bool
    {
        $postData     = new stdclass();
        $defaultField = array('key' => 0, 'nature' => '', 'analysis' => '', 'strategy' => '', 'name' => '', 'phone' => '', 'qq' => '', 'weixin' => '', 'email' => '');
        foreach($defaultField as $field => $defaultValue) $postData->{$field} = $defaultValue;
        foreach($params as $key => $value) $postData->{$key} = $value;

        $changes = $this->objectModel->edit($stakeholderID, $postData);

        if(dao::isError()) return dao::getError();
        return $changes;
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

    /**
     * 获取干系人account => realname键值对。
     * Get the stakeholder account => realname key-value pair.
     *
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getStakeHolderPairsTest(int $objectID): array
    {
        $stakeholderPairs = $this->objectModel->getStakeHolderPairs($objectID);

        if(dao::isError()) return dao::getError();
        return $stakeholderPairs;
    }
}
