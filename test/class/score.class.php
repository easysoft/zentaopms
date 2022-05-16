<?php
class scoreTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('score');
    }

    /**
     * Get user score list.
     *
     * @param string $account
     * @param object $pager
     * @param bool   $needCount
     *
     * @access public
     * @return array|int
     */
    public function getListByAccountTest($account, $pager, $needCount = false)
    {
        $objects = $this->objectModel->getListByAccount($account, $pager);

        if(dao::isError()) return dao::getError();

        return $needCount ? count($objects) : $objects;
    }

    /**
     * Add score logs.
     *
     * @param string $module
     * @param string $method
     * @param string $param
     * @param string $account
     * @param string $time
     *
     * @access public
     * @return string|object
     */
    public function createTest($module = '', $method = '', $param = '', $account = '', $time = '')
    {
        global $tester;

        $object = $this->objectModel->create($module, $method, $param, $account, $time);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            $object = (!empty((array) $object) and is_object($object)) ? $object : '';
        }

        return $object;
    }

    /**
     * Score reset.
     *
     * @param int $lastID
     *
     * @access public
     * @return array
     */
    public function resetTest($lastID = 0)
    {
        $result = $this->objectModel->reset($lastID);
        while($result['status'] != 'finish')
        {
            $result = $this->objectModel->reset($result['lastID']);
        }

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Fix action type for score.
     *
     * @param $string
     *
     * @access public
     * @return string
     */
    public function fixKeyTest($string)
    {
        $objects = $this->objectModel->fixKey($string);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
