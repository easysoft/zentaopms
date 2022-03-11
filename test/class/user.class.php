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
}
