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
     * @param  bool   $count
     * @access public
     * @return void
     */
    public function getListByAccountsTest($accounts = array(), $keyField = 'id', $count = false)
    {
        $objects = $this->objectModel->getListByAccounts($accounts, $keyField);
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
     * Test get user display infos.
     *
     * @param  int    $accounts
     * @param  int    $deptID
     * @param  string $type
     * @access public
     * @return void
     */
    public function getUserDisplayInfosTest($accounts, $deptID = 0, $type = 'inside')
    {
        $objects = $this->objectModel->getUserDisplayInfos($accounts, $deptID, $type);
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
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getByQueryTest($browseType = 'inside', $query = '', $orderBy = 'id')
    {
        $objects = $this->objectModel->getByQuery($browseType, $query, null, $orderBy);
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
     * Test create a user.
     *
     * @param  array $params
     * @access public
     * @return void
     */
    public function createUserTest($params = array())
    {
        $_POST  = $params;
        $_POST['verifyPassword'] = 'bac0bbaaf7192f219bebd5387e88c5d7';

        $userID = $this->objectModel->create();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->getByID($userID, 'id');
        }
    }

    /**
     * Test batch create users.
     *
     * @access public
     * @return void
     */
    public function batchCreateUserTest($params = array())
    {
        $_POST  = $params;
        $_POST['verifyPassword'] = 'bac0bbaaf7192f219bebd5387e88c5d7';
        $_POST['userType']       = 'inside';

        global $tester;
        $tester->config->user->batchCreate = count($_POST['account']);

        $userIDList = $this->objectModel->batchCreate();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $users = array();
            foreach($userIDList as $userID) $users[] = $this->objectModel->getByID($userID, 'id');

            return $users;
        }
    }

    /**
     * Test edit a user.
     *
     * @param  int   $userID
     * @param  array $params
     * @access public
     * @return void
     */
    public function updateUserTest($userID, $params = array())
    {
        $_POST = $params;
        $_POST['verifyPassword'] = 'bac0bbaaf7192f219bebd5387e88c5d7';

        $this->objectModel->update($userID);
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->getByID($userID, 'id');
        }
    }

    /**
     * Test batch edit users.
     *
     * @param  array $params
     * @access public
     * @return void
     */
    public function batchEditUserTest($params = array())
    {
        $_POST = $params;
        $_POST['verifyPassword'] = 'bac0bbaaf7192f219bebd5387e88c5d7';

        $this->objectModel->batchEdit();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $users      = array();
            $userIDList = array_keys($params['account']);
            foreach($userIDList as $userID)
            {
                $user = $this->objectModel->getByID($userID, 'id');
                $users[$user->id] = $user;
            }

            return $users;
        }
    }

    /**
     * Test edit a user password.
     *
     * @param  int   $userID
     * @param  array $params
     * @access public
     * @return void
     */
    public function updatePasswordTest($userID, $params = array())
    {
        $_POST = $params;

        $this->objectModel->updatePassword($userID);
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $this->objectModel->getByID($userID, 'id');
        }
    }

    /**
     * Reset user password.
     *
     * @param  array $params
     * @access public
     * @return void
     */
    public function resetPasswordTest($params = array())
    {
        $_POST = $params;

        $this->objectModel->resetPassword();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $account = $params['account'];
            return $this->objectModel->getByID($account, 'account');
        }
    }

    /**
     * Test check posted data before creating or editing a user.
     * 测试创建或编辑用户前检查提交的数据。
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
            if(is_array($error)) $errors[$key] = implode(',', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * Check user password.
     *
     * @param  array $params
     * @access public
     * @return void
     */
    public function checkPasswordTest($params = array())
    {
        $_POST = $params;

        $this->objectModel->checkPassword();
        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return '无报错';
        }
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
            if(is_array($error)) $errors[$key] = implode(',', $error);
        }

        return array('result' => (int)$result, 'errors' => $errors);
    }

    /**
     * Identify user.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function identifyTest($account, $password)
    {
        $user = $this->objectModel->identify($account, $password);
        unset($_POST);

        return $user;
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
     * Test login user.
     *
     * @param  object $user
     * @access public
     * @return void
     */
    public function loginTest($user)
    {
        $user = $this->objectModel->login($user);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $user;
        }
    }

    /**
     * Test get groups by user.
     *
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function getGroupsTest($account)
    {
        $groups = $this->objectModel->getGroups($account);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $groups;
        }
    }

    /**
     * Test get groups by visions.
     *
     * @param  string $visions
     * @access public
     * @return void
     */
    public function getGroupsByVisionsTest($visions)
    {
        $groups = $this->objectModel->getGroupsByVisions($visions);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $groups;
        }
    }

    /**
     * Test get my objects.
     *
     * @param  string $account
     * @param  string $type
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getObjectsTest($account, $type, $status, $orderBy)
    {
        $myObjects = $this->objectModel->getObjects($account, $type, $status, $orderBy);

        if(dao::isError()) return dao::getError();
        return $myObjects;
    }

    /**
     * Test clean user locked time.
     *
     * @param  string $account
     * @access public
     * @return string
     */
    public function cleanLockedTest($account)
    {
        global $tester;

        $this->objectModel->cleanLocked($account);

        if(dao::isError()) return dao::getError();

        $locked = $tester->dao->select('locked')->from(TABLE_USER)->where('account')->eq($account)->fetch('locked');

        return helper::isZeroDate($locked) ? 'success' : 'fail';
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
     * Test get list by account method.
     *
     * @param  string $account
     * @access public
     * @return void
     */
    public function getListByAccountTest($account)
    {
        return $this->objectModel->getListByAccount($account);
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
     * Test create contact list.
     *
     * @param  string $listName
     * @param  array  $userList
     * @access public
     * @return void
     */
    public function createContactListTest($listName = '', $userList = array())
    {
        $_POST = array();
        $_POST['listName'] = $listName;
        $_POST['userList'] = $userList;

        $listID  = $this->objectModel->createContactList();

        if(dao::isError()) return array('message' => dao::getError());
        global $dao;
        return $dao->select('*')->from(TABLE_USERCONTACT)->orderBy('id_desc')->fetch();
    }

    /**
     * Test update contact list.
     *
     * @param  int    $listID
     * @param  string $listName
     * @param  array  $userList
     * @access public
     * @return void
     */
    public function updateContactListTest($listID = 0, $listName = '', $userList = array())
    {
        $_POST = array();
        $_POST['listName'] = $listName;
        $_POST['userList'] = $userList;
        $this->objectModel->updateContactList($listID);

        if(dao::isError()) return array('message' => dao::getError());
        return $this->objectModel->getContactListByID($listID);
    }

    /**
     * Test delete a contact list.
     *
     * @param  int    $listID
     * @access public
     * @return void
     */
    public function deleteContactListTest($listID)
    {
        $this->objectModel->deleteContactList($listID);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getContactListByID($listID);
    }

    /**
     * Test get user data in json.
     *
     * @param  object $user
     * @access public
     * @return void
     */
    public function getDataInJSONTest($user)
    {
        $user = $this->objectModel->getDataInJSON($user);

        if(dao::isError()) return dao::getError();
        return $user;
    }

    /**
     * Test get weak users.
     *
     * @access public
     * @return void
     */
    public function getWeakUsersTest()
    {
        $users = $this->objectModel->getWeakUsers();

        if(dao::isError()) return dao::getError();
        return $users;
    }

    /**
     * Test compute password strength.
     *
     * @access public
     * @return void
     */
    public function computePasswordStrengthTest($password)
    {
        $strength = $this->objectModel->computePasswordStrength($password);

        if(dao::isError()) return dao::getError();
        return $strength;
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
     * Test check program priv.
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
        return $this->objectModel->checkProgramPriv($program, $account, $stakeholders, $whiteList, $admins);
    }

    /**
     * Test check project priv.
     *
     * @param  object $project
     * @param  string $account
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @access public
     * @return void
     */
    public function checkProjectPrivTest(object $project, string $account, array $stakeholders = array(), array $teams = array(), array $whiteList = array(), array $admins = array()): bool
    {
        return $this->objectModel->checkProjectPriv($project, $account, $stakeholders, $teams, $whiteList, $admins);
    }
    /**
     * Test check sprint priv.
     *
     * @param  object $sprint
     * @param  string $account
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @access public
     * @return void
     */
    public function checkSprintPrivTest($sprint, $account, $stakeholders = array(), $teams = array(), $whiteList = array())
    {
        return $this->objectModel->checkSprintPriv($sprint, $account, $stakeholders, $teams, $whiteList);
    }
    /**
     * Test check product priv.
     *
     * @param  object $program
     * @param  string $account
     * @param  array  $groups
     * @param  array  $teams
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return bool
     */
    public function checkProductPrivTest(object $product, string $account, string $groups = '', array $teams = array(), array $stakeholders = array(), array $whiteList = array(), array $admins = array()): bool
    {
        return $this->objectModel->checkProductPriv($product, $account, $groups, $teams, $stakeholders, $whiteList, $admins);
    }

    /**
     * Test get project authed users.
     *
     * @param  int    $projectID
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @access public
     * @return void
     */
    public function getProjectAuthedUsersTest($projectID, $stakeholders, $teams, $whiteList)
    {
        global $tester;
        $project = $tester->loadModel('project')->getByID($projectID);
        return $this->objectModel->getProjectAuthedUsers($project, $stakeholders, $teams, $whiteList);
    }

    /**
     * Test get program authed users.
     *
     * @param  int    $projectID
     * @param  array  $stakeholders
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return array
     */
    public function getProgramAuthedUsersTest(int $programID, array $stakeholders, array $whiteList, array $admins): array
    {
        global $tester;
        $program = $tester->loadModel('program')->getByID($programID);
        return $this->objectModel->getProgramAuthedUsers($program, $stakeholders, $whiteList, $admins);
    }

    /**
     * getSprintAuthedUsersTest
     *
     * @param  int    $sprintID
     * @param  array  $stakeholders
     * @param  array  $teams
     * @param  array  $whiteList
     * @param  array  $admins
     * @access public
     * @return void
     */
    public function getSprintAuthedUsersTest($sprintID, $stakeholders, $teams, $whiteList, $admins)
    {
        global $tester;
        $sprint = $tester->loadModel('execution')->getByID($sprintID);
        return $this->objectModel->getSprintAuthedUsers($sprint, $stakeholders, $teams, $whiteList, $admins);
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
     * Test save user template.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function saveUserTemplate($type)
    {
        global $tester;
        $this->objectModel->saveUserTemplate($type);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getUserTemplates($type);
    }

    /**
     * Test get user templates.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getUserTemplatesTest($type)
    {
        return $this->objectModel->getUserTemplates($type);
    }

    /**
     * Test get person data.
     *
     * @param  string $account
     * @access public
     * @return void
     */
    public function getPersonalDataTest($account)
    {
        return $this->objectModel->getPersonalData($account);
    }

    /**
     * Test get user details for api.
     *
     * @param  array $userList
     * @access public
     * @return void
     */
    public function getListForGitLabAPITest($userList)
    {
        return $this->objectModel->getListForGitLabAPI($userList);
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
     * Put the current user first
     *
     * @param  array    $users
     * @access public
     * @return array
     */
    public function setCurrentUserFirstTest($users = array())
    {
        return $this->objectModel->setCurrentUserFirst($users);
    }

    /**
     * Judge a user is logon or not.
     *
     * @access public
     * @return bool
     */
    public function isLogonTest()
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
     * Plus the fail times.
     *
     * @param  string  $account
     * @access public
     * @return string
     */
    public function checkLockedTest($account)
    {
        global $tester;
        $result = $this->objectModel->checkLocked($account);

        $result = $result ? 'locked' : 'unlocked';

        return $result;
    }

    /**
     * Identify user by PHP_AUTH_USER.
     *
     * @access public
     * @return bool
     */
    public function identifyByPhpAuthTest()
    {
        return $this->objectModel->identifyByPhpAuth();
    }

    /**
     * Identify user by cookie.
     *
     * @access public
     * @return bool
     */
    public function identifyByCookieTest()
    {
        return $this->objectModel->identifyByCookie();
    }

    /**
     * test of isClickable.
     *
     * @param  int    $userID
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest($userID, $action = '')
    {
        global $tester;

        $user = $tester->loadModel('user')->getById($userID, 'id');
        if($userID == 10) $user->ranzhi = '';

        return $this->objectModel->isClickable($user, $action);
    }
}
