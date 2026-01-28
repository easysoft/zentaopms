<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class userModelTest extends baseTest
{
    protected $moduleName = 'user';
    protected $className  = 'model';

    /**
     * Test get user list.
     *
     * @param  bool $count
     * @access public
     * @return void
     */
    public function getListTest($params = 'nodeleted', $count = false)
    {
        $objects = $this->instance->getList($params);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $count ? count($objects) : $objects;
    }

    /**
     * Test get user information by accounts.
     *
     * @param  array  $accounts
     * @param  string $keyField
     * @access public
     * @return void
     */
    public function getListByAccountsTest($accounts = array(), $keyField = 'id')
    {
        $objects = $this->instance->getListByAccounts($accounts, $keyField);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test su method.
     *
     * @access public
     * @return mixed
     */
    public function suTest()
    {
        global $app;

        $result = $this->instance->su();
        if(dao::isError()) return dao::getError();

        $responseData = new stdClass();
        $responseData->result      = $result;
        $responseData->currentUser = $app->user;

        return $responseData;
    }

    /**
     * Test user get pairs.
     *
     * @param  string $params
     * @param  string $usersToAppended
     * @param  int    $maxCount
     * @param  array  $accounts
     * @access public
     * @return void
     */
    public function getPairsTest($params = '', $usersToAppended = '', $maxCount = 0, $accounts = array())
    {
        $objects = $this->instance->getPairs($params, $usersToAppended, $maxCount, $accounts);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * 测试根据用户和状态获取项目列表。
     * Test get projects by user and status.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectsTest(string $account, string $status = 'all', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->instance->getProjects($account, $status, $orderBy, $pager);
    }

    /**
     * 测试获取某个用户参与的项目和项目中指派给他的任务数的键值对。
     * Test get executions that the user joined and the task count of the execution.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionsTest(string $account, string $status = 'all', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        return $this->instance->getExecutions($account, $status, $orderBy, $pager);
    }

    /**
     * 根据用户名获取用户信息。
     * Get user information by account.
     *
     * @param  string|array $usersToAppended
     * @param  string       $fields
     * @param  string       $keyField
     * @access public
     * @return array
     */
    public function fetchExtraUsersTest(string|array $usersToAppended, string $fields, string $keyField): array
    {
        return $this->instance->fetchExtraUsers($usersToAppended, $fields, $keyField);
    }

    /**
     * 检测处理用户显示名的功能。
     * Test process display value.
     *
     * @param  array  $users
     * @param  string $params
     * @access public
     * @return array
     */
    public function processDisplayValueTest(array $users, string $params): array
    {
        return $this->instance->processDisplayValue($users, $params);
    }

    /**
     * Test get avatar pairs.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getAvatarPairsTest($param = 'nodeleted')
    {
        $objects = $this->instance->getAvatarPairs($param);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test get user commiters.
     *
     * @access public
     * @return void
     */
    public function getCommitersTest()
    {
        $objects = $this->instance->getCommiters();
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test get user realname and emails.
     *
     * @param  array $accounts
     * @access public
     * @return void
     */
    public function getRealNameAndEmailsTest($accounts)
    {
        $objects = $this->instance->getRealNameAndEmails($accounts);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test get user roles.
     *
     * @param  array  $accounts
     * @param  bool   $needRole
     * @access public
     * @return void
     */
    public function getUserRolesTest($accounts, $needRole = false)
    {
        $objects = $this->instance->getUserRoles($accounts, $needRole);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test get user by id.
     *
     * @param  int|string $userID
     * @param  string     $field
     * @access public
     * @return void
     */
    public function getByIdTest($userID, $field = 'account')
    {
        $objects = $this->instance->getById($userID, $field);
        if(dao::isError())
        {
             $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * Test get user by query.
     *
     * @param  string $browseType
     * @param  string $query
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getByQueryTest($browseType = 'inside', $query = '', $pager = null, $orderBy = 'id')
    {
        $objects = $this->instance->getByQuery($browseType, $query, $pager, $orderBy);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        return $objects;
    }

    /**
     * 测试创建一个用户。
     * Test create a user.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function createTest(object $user): array
    {
        $result = $this->instance->create($user);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试创建一个外部公司。
     * Test create a company.
     *
     * @param  string $companyName
     * @access public
     * @return array
     */
    public function createCompanyTest(string $companyName): array
    {
        $result = $this->instance->createCompany($companyName);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试创建用户权限组。
     * Test create user groups.
     *
     * @param  array  $groups
     * @param  string $account
     * @access public
     * @return array
     */
    public function createUserGroupTest(array $groups, string $account): array
    {
        $result = $this->instance->createUserGroup($groups, $account);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试批量创建用户。
     * Test batch create users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return array
     */
    public function batchCreateTest(array $users, string $verifyPassword): array
    {
        global $tester;
        $tester->config->user->batchCreate = count($users);

        foreach(array_keys($users) as $index) $_POST['company'][$index] = 'ditto';
        $_POST['company']['0'] = $verifyPassword;
        $result = $this->instance->batchCreate($users, $verifyPassword);
        $errors = dao::getError();
        unset($_POST);

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (is_array($result) && !empty($result)) ? implode(',', $result) : (int)$result, 'errors' => $errors);
    }

    /**
     * 测试编辑一个用户。
     * Test edit a user.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function updateTest(object $user): array
    {
        $result = $this->instance->update($user);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试检测用户名是否更改。
     * Test check account change.
     *
     * @param  string $oldAccount
     * @param  string $newAccount
     * @access public
     * @return array
     */
    public function checkAccountChangeTest(string $oldAccount, string $newAccount): array
    {
        $result = $this->instance->checkAccountChange($oldAccount, $newAccount);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试检测权限组是否更改。
     * Test check group change.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function checkGroupChangeTest(object $user): array
    {
        $result = $this->instance->checkGroupChange($user);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试批量更新用户。
     * Test batch update users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return array
     */
    public function batchUpdateTest(array $users, string $verifyPassword): array
    {
        $result = $this->instance->batchUpdate($users, $verifyPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试更新当前用户的密码。
     * Test update password of current user.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function updatePasswordTest(object $user): array
    {
        $result = $this->instance->updatePassword($user);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 重置用户密码。
     * Reset user password.
     *
     * @param  object $user
     * @access public
     * @return array
     */
    public function resetPasswordTest(object $user): array
    {
        $result = $this->instance->resetPassword($user);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试创建或编辑用户前检查提交的数据。
     * Test check posted data before creating or editing a user.
     *
     * @param  object $user
     * @param  bool   $canNoPassword
     * @access public
     * @return array
     */
    public function checkBeforeCreateOrEditTest(object $user, bool $canNoPassword = false): array
    {
        $result = $this->instance->checkBeforeCreateOrEdit($user, $canNoPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试批量创建用户前检查提交的数据。
     * Test check posted data before batch creating users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return array
     */
    public function checkBeforeBatchCreateTest(array $users, string $verifyPassword): array
    {
        $result = $this->instance->checkBeforeBatchCreate($users, $verifyPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试批量编辑用户前检查提交的数据。
     * Test check posted data before batch editing users.
     *
     * @param  array  $users
     * @param  string $verifyPassword
     * @access public
     * @return array
     */
    public function checkBeforeBatchUpdateTest(array $users, string $verifyPassword): array
    {
        $result = $this->instance->checkBeforeBatchUpdate($users, $verifyPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试检查提交的用户密码。
     * Test check posted user password.
     *
     * @param  object $user
     * @param  bool   $canNoPassword
     * @access public
     * @return void
     */
    public function checkPasswordTest(object $user, bool $canNoPassword = false): array
    {
        $result = $this->instance->checkPassword($user, $canNoPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试验证当前用户登录密码的功能。
     * Test check verify password of current user.
     *
     * @param  string $verifyPassword
     * @access public
     * @return array
     */
    public function checkVerifyPasswordTest(string $verifyPassword): array
    {
        $result = $this->instance->checkVerifyPassword($verifyPassword);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试验证用户。
     * Identify user.
     *
     * @param  string $account
     * @param  string $password
     * @param  int    $passwordStrength
     * @access public
     * @return object|bool
     */
    public function identifyTest(string $account, string $password, int $passwordStrength = 0): object|bool
    {
        return $this->instance->identify($account, $password, $passwordStrength);
    }

    /**
     * 测试验证用户。
     * Identify user.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return object|bool
     */
    public function identifyUserTest(string $account, string $password): object|bool
    {
        return $this->instance->identifyUser($account, $password);
    }

    /**
     * 测试检查是否需要修改密码。
     * Test check need modify password.
     *
     * @param  object $user
     * @param  int    $passwordStrength
     * @access public
     * @return object
     */
    public function checkNeedModifyPasswordTest(object $user, int $passwordStrength): object
    {
        return $this->instance->checkNeedModifyPassword($user, $passwordStrength);
    }

    /**
     * Test authorize user.
     *
     * @param  string $account
     * @access public
     * @return void
     */
    public function authorizeTest($account)
    {
        return $this->instance->authorize($account);
    }

    /**
     * 测试用户登录。
     * Test login user.
     *
     * @param  object $user
     * @param  bool   $addAction
     * @param  bool   $keepLogin
     * @access public
     * @return object
     */
    public function loginTest(object $user, bool $addAction = true, bool $keepLogin = false): bool|object
    {
        return $this->instance->login($user, $addAction, $keepLogin);
    }

    /**
     * 测试获取某个用户的权限组。
     * Test get groups of a user.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getGroupsTest(string $account): array
    {
        return $this->instance->getGroups($account);
    }

    /**
     * 测试获取某些界面下可用的权限组。
     * Test get groups by visions.
     *
     * @param  string|array $visions
     * @access public
     * @return array
     */
    public function getGroupsByVisionsTest(string|array $visions): array
    {
        return $this->instance->getGroupsByVisions($visions);
    }

    /**
     * 测试清除用户失败次数和锁定时间。
     * Test clean user locked time.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function cleanLockedTest(string $account): array
    {
        $result = $this->instance->cleanLocked($account);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * Test unbind Ranzhi.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function unbindTest($account)
    {
        $this->instance->unbind($account);
        if(dao::isError()) return dao::getError();

        $ranzhi = $this->instance->dao->select('ranzhi')->from(TABLE_USER)->where('account')->eq($account)->fetch('ranzhi');
        $result = empty($ranzhi) ? 'success' : 'fail';
        return $result;
    }

    /**
     * Test get contact list.
     *
     * @param  string $account
     * @param  string $params
     * @access public
     * @return void
     */
    public function getContactListsTest($account = '', $params = '')
    {
        $contacts = $this->instance->getContactLists($account, $params);
        if(dao::isError()) return dao::getError();
        return $contacts ? $contacts : 0;
    }

    /**
     * Test get parent stage authed users.
     *
     * @param  int    $stageID
     * @access public
     * @return void
     */
    public function getParentStageAuthedUsersTest($stageID)
    {
        return $this->instance->getParentStageAuthedUsers($stageID);
    }

    /**
     * Test get contact list by id.
     *
     * @param  int    $listID
     * @access public
     * @return void
     */
    public function getContactListByIDTest($listID)
    {
        return $this->instance->getContactListByID($listID);
    }

    /**
     * 测试创建联系人列表。
     * Test create contact list.
     *
     * @param  object $userContact
     * @access public
     * @return array
     */
    public function createContactListTest(object $userContact): array
    {
        $result = $this->instance->createContactList($userContact);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试更新联系人列表。
     * Test update contact list.
     *
     * @param  object $userContact
     * @access public
     * @return array
     */
    public function updateContactListTest(object $userContact): array
    {
        $result = $this->instance->updateContactList($userContact);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试删除联系人列表。
     * Test delete a contact list.
     *
     * @param  int    $listID
     * @access public
     * @return array
     */
    public function deleteContactListTest(int $listID): array
    {
        $result = $this->instance->deleteContactList($listID);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试获取弱密码用户。
     * Test get users with weak password.
     *
     * @access public
     * @return array
     */
    public function getWeakUsersTest(): array
    {
        return $this->instance->getWeakUsers();
    }

    /**
     * 测试计算密码强度。
     * Test compute password strength.
     *
     * @param  string $password
     * @access public
     * @return int
     */
    public function computePasswordStrengthTest(string $password): int
    {
        return $this->instance->computePasswordStrength($password);
    }

    /**
     * Test compute user view.
     *
     * @param  string $account
     * @param  bool   $force
     * @access public
     * @return void
     */
    public function computeUserViewTest($account, $force = true)
    {
        $userview = $this->instance->computeUserView($account, $force);
        if(dao::isError()) return dao::getError();
        return $userview;
    }

    /**
     * Test get product members.
     *
     * @param  string $account
     * @param  bool   $force
     * @access public
     * @return void
     */
    public function getProductMembersTest($allProducts)
    {
        $members = $this->instance->getProductMembers($allProducts);
        if(dao::isError()) return dao::getError();
        return $members;
    }

    /**
     * Test get product members.
     *
     * @param  string $account
     * @param  bool   $force
     * @access public
     * @return void
     */
    public function grantUserViewTest($account = '', $acls = array(), $projects = '')
    {
        $userView = $this->instance->grantUserView($account, $acls, $projects);
        if(dao::isError()) return dao::getError();
        return $userView;
    }

    /**
     * Test update program view.
     *
     * @param  array  $programIdList
     * @param  array  $users
     * @access public
     * @return void
     */
    public function updateProgramViewTest($programIdList = array(), $users = array())
    {
        $this->instance->updateProgramView($programIdList, $users);
        if(dao::isError()) return dao::getError();
        return $this->instance->grantUserView(current($users));
    }

    /**
     * Test get product view list users.
     *
     * @param  int    $productID
     * @param  array  $teams
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @access public
     * @return void
     */
    public function getProductViewListUsersTest($productID, $teams, $stakeholders, $whiteList, $admins)
    {
        $product = $this->instance->loadModel('product')->getByID($productID);
        return $this->instance->getProductViewListUsers($product, $teams, $stakeholders, $whiteList, $admins);
    }

    /**
     * Test get team member pairs of the object.
     *
     * @param  string|array|int $objectIds
     * @param  string           $type            project|execution
     * @param  string           $params
     * @param  string|array     $usersToAppended
     * @access public
     * @return array
     */
    public function getTeamMemberPairsTest(string|array|int $objectIds, string $type = 'project', string $params = '', string|array $usersToAppended = '')
    {
        return $this->instance->getTeamMemberPairs($objectIds, $type, $params, $usersToAppended);
    }

    /**
     * 测试保存用户模板。
     * Test save user template.
     *
     * @param  object $template
     * @access public
     * @return array
     */
    public function saveUserTemplateTest(object $template): array
    {
        $result = $this->instance->saveUserTemplate($template);
        $errors =  dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * 测试获取指定类型的用户模板。
     * Test get user templates.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getUserTemplatesTest(string $type): array
    {
        return $this->instance->getUserTemplates($type);
    }

    /**
     * Test get user details for api.
     *
     * @param  array $accountList
     * @access public
     * @return array
     */
    public function getListForGitLabAPITest(array $accountList): array
    {
        return $this->instance->getListForGitLabAPI($accountList);
    }

    /**
     * Get users who have authority to create stories.
     *
     * @access public
     * @return array
     */
    public function getCanCreateStoryUsersTest()
    {
        return $this->instance->getCanCreateStoryUsers();
    }

    /**
     * 判断用户是否登录。
     * Judge a user is logon or not.
     *
     * @access public
     * @return bool
     */
    public function isLogonTest(): bool
    {
        return $this->instance->isLogon();
    }

    /**
     * Plus the fail times.
     *
     * @param  string  $account
     * @access public
     * @return int
     */
    public function failPlusTest($account)
    {
        $result = $this->instance->failPlus($account);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试检查用户是否被锁定。
     * Test check user is locked or not.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function checkLockedTest(string $account): bool
    {
        return $this->instance->checkLocked($account);
    }

    /**
     * 测试根据 PHP_AUTH_USER 验证用户。
     * Identify user by PHP_AUTH_USER.
     *
     * @access public
     * @return bool
     */
    public function identifyByPhpAuthTest(): bool
    {
        return $this->instance->identifyByPhpAuth();
    }

    /**
     * 测试根据 cookie 验证用户。
     * Identify user by cookie.
     *
     * @access public
     * @return bool
     */
    public function identifyByCookieTest(): bool
    {
        return $this->instance->identifyByCookie();
    }

    /**
     * 测试是否可以针对用户执行某个操作。
     * Test whether an action can be performed on the user.
     *
     * @param  object $user
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $user, string $action): bool
    {
        return $this->instance->isClickable($user, $action);
    }

    /**
     * Test getUserAcls method.
     *
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function getUserAclsTest(string $account)
    {
        $result = $this->invokeArgs('getUserAcls', [$account]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test uploadAvatar method.
     *
     * @access public
     * @return array
     */
    public function uploadAvatarTest(): array
    {
        $result = $this->instance->uploadAvatar();
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test initViewObjects method.
     *
     * @param  bool $force
     * @access public
     * @return mixed
     */
    public function initViewObjectsTest(bool $force = false)
    {
        $result = $this->invokeArgs('initViewObjects', [$force]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProgramView method.
     *
     * @param  string $account
     * @param  array  $allPrograms
     * @param  array  $manageObjects
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $programStakeholderGroup
     * @access public
     * @return mixed
     */
    public function getProgramViewTest(string $account = '', array $allPrograms = array(), array $manageObjects = array(), array $stakeholders = array(), array $whiteList = array(), array $programStakeholderGroup = array())
    {
        $result = $this->invokeArgs('getProgramView', [$account, $allPrograms, $manageObjects, $stakeholders, $whiteList, $programStakeholderGroup]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProductView method.
     *
     * @param  string $account
     * @param  array  $allProducts
     * @param  array  $manageObjects
     * @param  array  $whiteList
     * @access public
     * @return mixed
     */
    public function getProductViewTest(string $account = '', array $allProducts = array(), array $manageObjects = array(), array $whiteList = array())
    {
        $result = $this->invokeArgs('getProductView', [$account, $allProducts, $manageObjects, $whiteList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProjectView method.
     *
     * @param  string $account
     * @param  array  $allProjects
     * @param  array  $manageObjects
     * @param  array  $teams
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $projectStakeholderGroup
     * @access public
     * @return mixed
     */
    public function getProjectViewTest(string $account = '', array $allProjects = array(), array $manageObjects = array(), array $teams = array(), array $stakeholders = array(), array $whiteList = array(), array $projectStakeholderGroup = array())
    {
        $result = $this->invokeArgs('getProjectView', [$account, $allProjects, $manageObjects, $teams, $stakeholders, $whiteList, $projectStakeholderGroup]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getManageListGroupByType method.
     *
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function getManageListGroupByTypeTest(string $account = '')
    {
        $result = $this->invokeArgs('getManageListGroupByType', [$account]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProgramStakeholder method.
     *
     * @param  mixed $programProduct
     * @access public
     * @return mixed
     */
    public function getProgramStakeholderTest($programProduct = null)
    {
        $result = $this->invokeArgs('getProgramStakeholder', [$programProduct]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test mergeAclsToUserView method.
     *
     * @param  string $account
     * @param  object $userView
     * @param  array  $acls
     * @param  string $projects
     * @access public
     * @return mixed
     */
    public function mergeAclsToUserViewTest(string $account, object $userView, array $acls, string $projects)
    {
        $result = $this->invokeArgs(['mergeAclsToUserView', [$account, $userView, $acls, $projects]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getObjectsAuthedUsers method.
     *
     * @param  array  $objects
     * @param  string $objectType
     * @param  array  $stakeholderGroup
     * @param  array  $teamsGroup
     * @param  array  $whiteListGroup
     * @param  array  $adminsGroup
     * @param  array  $parentStakeholderGroup
     * @param  array  $parentPMGroup
     * @access public
     * @return array
     */
    public function getObjectsAuthedUsersTest(array $objects, string $objectType, array $stakeholderGroup = array(), array $teamsGroup = array(), array $whiteListGroup = array(), array $adminsGroup = array(), array $parentStakeholderGroup = array(), array $parentPMGroup = array()): array
    {
        $result = $this->invokeArgs('getObjectsAuthedUsers', [$objects, $objectType, $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup, $parentStakeholderGroup, $parentPMGroup]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLatestUserView method.
     *
     * @param  string $account
     * @param  string $view
     * @param  object $object
     * @param  string $objectType
     * @param  array  $stakeholderGroup
     * @param  array  $teamsGroup
     * @param  array  $whiteListGroup
     * @param  array  $adminsGroup
     * @param  array  $parentStakeholderGroup
     * @param  array  $parentPMGroup
     * @access public
     * @return string
     */
    public function getLatestUserViewTest(string $account, string $view, object $object, string $objectType, array $stakeholderGroup = array(), array $teamsGroup = array(), array $whiteListGroup = array(), array $adminsGroup = array(), array $parentStakeholderGroup = array(), array $parentPMGroup = array()): string
    {
        $result = $this->invokeArgs('getLatestUserView', [$account, $view, $object, $objectType, $stakeholderGroup, $teamsGroup, $whiteListGroup, $adminsGroup, $parentStakeholderGroup, $parentPMGroup]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProgramPriv method.
     *
     * @param  object $program
     * @param  string $account
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProgramPrivTest(object $program, string $account, array $stakeholders = array(), array $whiteList = array(), array $admins = array()): bool
    {
        $result = $this->invokeArgs('checkProgramPriv', [$program, $account, $stakeholders, $whiteList, $admins]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProjectPriv method.
     *
     * @param  object $project
     * @param  string $account
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProjectPrivTest(object $project, string $account, array $stakeholders = array(), array $teams = array(), array $whiteList = array(), array $admins = array()): bool
    {
        $result = $this->invokeArgs('checkProjectPriv', [$project, $account, $stakeholders, $teams, $whiteList, $admins]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkProductPriv method.
     *
     * @param  object $product
     * @param  string $account
     * @param  array  $teams
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProductPrivTest(object $product, string $account, array $teams = array(), array $stakeholders = array(), array $whiteList = array(), array $admins = array()): bool
    {
        $result = $this->invokeArgs('checkProductPriv', [$product, $account, $teams, $stakeholders, $whiteList, $admins]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProgramAuthedUsers method.
     *
     * @param  object $program
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProgramAuthedUsersTest(object $program, array $stakeholders = array(), array $whiteList = array(), array $admins = array()): array
    {
        $result = $this->invokeArgs('getProgramAuthedUsers', [$program, $stakeholders, $whiteList, $admins]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProjectAuthedUsers method.
     *
     * @param  object $project
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProjectAuthedUsersTest(object $project, array $stakeholders = array(), array $teams = array(), array $whiteList = array(), array $admins = array()): array
    {
        $result = $this->invokeArgs('getProjectAuthedUsers', [$project, $stakeholders, $teams, $whiteList, $admins]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 测试保存旧版用户模板方法。
     * Test saveOldUserTemplate method.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function saveOldUserTemplateTest(string $type): array
    {
        $this->instance->saveOldUserTemplate($type);
        $errors = dao::getError();

        foreach($errors as $key => $error)
        {
            if(is_array($error)) $errors[$key] = implode('', $error);
        }

        $result = dao::isError() ? 0 : 1;
        return array('result' => $result, 'errors' => $errors);
    }
}
