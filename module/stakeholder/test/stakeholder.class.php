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

    /**
     * 获取干系人信息。
     * Get stakeholder by id.
     *
     * @param  int               $stakeholderID
     * @access public
     * @return array|object|bool
     */
    public function getByIDTest(int $stakeholderID): array|object|bool
    {
        $stakeholder = $this->objectModel->getByID($stakeholderID);

        if(dao::isError()) return dao::getError();
        return $stakeholder;
    }

    /**
     * 获取给定用户ID的期望信息。
     * Get the expect information by user ID.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getExpectByUserTest(int $userID): array
    {
        $expects = $this->objectModel->getExpectByUser($userID);

        if(dao::isError()) return dao::getError();
        return $expects;
    }

    /**
     * 添加一条期望记录。
     * Add a expect record.
     *
     * @param  array        $data
     * @access public
     * @return array|object
     */
    public function expectTest(array $data): array|object
    {
        $defaultFields = array('userID' => 1, 'project' => 11, 'createdBy' => 'admin', 'createdDate' => date('Y-m-d'), 'expect' => '', 'progress' => '');
        $expectData    = new stdclass();
        foreach($defaultFields as $field => $defaultValue) $expectData->{$field} = $defaultValue;
        foreach($data as $key => $value) $expectData->{$key} = $value;

        $expectID = $this->objectModel->expect($expectData);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_EXPECT)->where('id')->eq($expectID)->fetch();
    }

    /**
     * 获取按照对象ID分组的干系人列表。
     * Get stakeholder group by object id.
     *
     * @param  array  $objectIdList
     * @access public
     * @return array
     */
    public function getStakeholderGroupTest(array $objectIdList): array
    {
        $stakeholderGroup = $this->objectModel->getStakeholderGroup($objectIdList);

        if(dao::isError()) return dao::getError();
        return $stakeholderGroup;
    }
}
