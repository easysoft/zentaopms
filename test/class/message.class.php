<?php
class messageTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('message');
    }

    public function getMessagesTest($status)
    {
        $objects = $this->objectModel->getMessages($status);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getObjectTypesTest()
    {
        $objects = $this->objectModel->getObjectTypes();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getObjectActionsTest()
    {
        $objects = $this->objectModel->getObjectActions();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sendTest($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $objects = $this->objectModel->send($objectType, $objectID, $actionType, $actionID, $actor = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveNoticeTest($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $objects = $this->objectModel->saveNotice($objectType, $objectID, $actionType, $actionID, $actor = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getToListTest($objectType)
    {
        global $tester;
        $table   = $tester->config->objectTables[$objectType]; 
        $object  = $tester->dao->select('*')->from($table)->where('id')->eq('1')->fetch();
        $objects = $this->objectModel->getToList($object, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getNoticeTodosTest()
    {
        $objects = $this->objectModel->getNoticeTodos();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
