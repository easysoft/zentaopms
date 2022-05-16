<?php
class scoreTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('score');
    }

    public function getListByAccountTest($account, $pager, $needCount = false)
    {
        $objects = $this->objectModel->getListByAccount($account, $pager);

        if(dao::isError()) return dao::getError();

        return $needCount ? count($objects) : $objects;
    }

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

    public function saveScoreTest($account = '', $rule = array(), $module = '', $method = '', $desc = '', $time = '')
    {
        $objects = $this->objectModel->saveScore($account, $rule, $module, $method, $desc, $time);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

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

    public function fixKeyTest($string)
    {
        $objects = $this->objectModel->fixKey($string);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getNoticeTest()
    {
        $objects = $this->objectModel->getNotice();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
