<?php
declare(strict_types = 1);
class userTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('user');
    }

    /**
     * Test get user list.
     *
     * @param  bool $count
     * @access public
     * @return void
     */
    public function getListTest($params = 'nodeleted', $count = false)
    {
        $objects = $this->objectModel->getList($params);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $count ? count($objects) : $objects;
        }
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
        $objects = $this->objectModel->getListByAccounts($accounts, $keyField);
        if(!dao::isError()) return $objects;

        $error = dao::getError();
        return $error[0];
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
        $objects = $this->objectModel->getPairs($params, $usersToAppended, $maxCount, $accounts);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $objects = $this->objectModel->getAvatarPairs($param);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
    }

    /**
     * Test get user commiters.
     *
     * @access public
     * @return void
     */
    public function getCommitersTest()
    {
        $objects = $this->objectModel->getCommiters();
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $objects = $this->objectModel->getRealNameAndEmails($accounts);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $objects = $this->objectModel->getUserRoles($accounts, $needRole);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $objects = $this->objectModel->getById($userID, $field);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $objects = $this->objectModel->getByQuery($browseType, $query, $pager, $orderBy);
        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        else
        {
            return $objects;
        }
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
        $result = $this->objectModel->create($user);
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

        $result = $this->objectModel->batchCreate($users, $verifyPassword);
        $errors = dao::getError();

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
        $result = $this->objectModel->update($user);
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
        $result = $this->objectModel->batchUpdate($users, $verifyPassword);
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
        $result = $this->objectModel->updatePassword($user);
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
        $result = $this->objectModel->resetPassword($user);
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
        $result = $this->objectModel->checkBeforeCreateOrEdit($user, $canNoPassword);
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
        $result = $this->objectModel->checkBeforeBatchCreate($users, $verifyPassword);
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
        $result = $this->objectModel->checkBeforeBatchUpdate($users, $verifyPassword);
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
        $result = $this->objectModel->checkPassword($user, $canNoPassword);
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
        $result = $this->objectModel->checkVerifyPassword($verifyPassword);
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
        return $this->objectModel->identify($account, $password, $passwordStrength);
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
        $user = $this->objectModel->authorize($account);

        return $user;
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
        return $this->objectModel->login($user, $addAction, $keepLogin);
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
        return $this->objectModel->getGroups($account);
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
        return $this->objectModel->getGroupsByVisions($visions);
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
        $result = $this->objectModel->cleanLocked($account);
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
        global $tester;

        $this->objectModel->unbind($account);

        if(dao::isError()) return dao::getError();

        $ranzhi = $tester->dao->select('ranzhi')->from(TABLE_USER)->where('account')->eq($account)->fetch('ranzhi');

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
        $contacts = $this->objectModel->getContactLists($account, $params);

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
        return $this->objectModel->getParentStageAuthedUsers($stageID);
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
        return $this->objectModel->getContactListByID($listID);
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
        $result = $this->objectModel->createContactList($userContact);
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
        $result = $this->objectModel->updateContactList($userContact);
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
        $result = $this->objectModel->deleteContactList($listID);
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
        return $this->objectModel->getWeakUsers();
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
        return $this->objectModel->computePasswordStrength($password);
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
        $userview = $this->objectModel->computeUserView($account, $force);

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
        $members = $this->objectModel->getProductMembers($allProducts);

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
        $userView = $this->objectModel->grantUserView($account, $acls, $projects);

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
        $this->objectModel->updateProgramView($programIdList, $users);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->grantUserView(current($users));
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
        global $tester;
        $product = $tester->loadModel('product')->getByID($productID);
        return $this->objectModel->getProductViewListUsers($product, $teams, $stakeholders, $whiteList, $admins);
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
        return $this->objectModel->getTeamMemberPairs($objectIds, $type, $params, $usersToAppended);
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
        $result = $this->objectModel->saveUserTemplate($template);
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
        return $this->objectModel->getUserTemplates($type);
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
        return $this->objectModel->getListForGitLabAPI($accountList);
    }

    /**
     * Get users who have authority to create stories.
     *
     * @access public
     * @return array
     */
    public function getCanCreateStoryUsersTest()
    {
        return $this->objectModel->getCanCreateStoryUsers();
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
        return $this->objectModel->isLogon();
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
        global $tester;
        $this->objectModel->failPlus($account);
        $failCounts = $tester->dao->select('fails')->from(TABLE_USER)->where('account')->eq($account)->fetch('fails');

        return $failCounts;
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
        return $this->objectModel->checkLocked($account);
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
        return $this->objectModel->identifyByPhpAuth();
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
        return $this->objectModel->identifyByCookie();
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
        return $this->objectModel->isClickable($user, $action);
    }
}
