<?php
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
    public function getListTest($count = false)
    {
        $objects = $this->objectModel->getList();
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
     * @param  bool   $count 
     * @access public
     * @return void
     */
    public function getListByAccountsTest($accounts = array(), $count = false)
    {
        $objects = $this->objectModel->getListByAccounts($accounts);
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
     * @access public
     * @return void
     */
    public function getAvatarPairsTest()
    {
        $objects = $this->objectModel->getAvatarPairs();
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
        $_POST['verifyPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';

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
        $_POST['verifyPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';

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
        $_POST['verifyPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';

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
        $_POST['verifyPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';

        $this->objectModel->batchEdit($params);
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
     * Identify user.
     * 
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function identifyTest($account, $password)
    {
        $_POST = $params;

        $user = $this->objectModel->identify($account, $password);
        unset($_POST);

        return $user;
    }

    /**
     * Test authorize user.
     * 
     * @param  string $account
     * @param  string $password
     * @access public
     * @return void
     */
    public function authorizeTest($account)
    {
        $user = $this->objectModel->authorize($account);

        return $user;
    }
}
