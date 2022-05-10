<?php
class compileTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('compile');
    }

    public function getByIDTest($buildID)
    {
        $objects = $this->objectModel->getByID($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByQueueTest($queue)
    {
        $objects = $this->objectModel->getByQueue($queue);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListTest($jobID, $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($jobID, $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListByJobIDTest($jobID)
    {
        $objects = $this->objectModel->getListByJobID($jobID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUnexecutedListTest()
    {
        $objects = $this->objectModel->getUnexecutedList();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLastResultTest($jobID)
    {
        $objects = $this->objectModel->getLastResult($jobID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSuccessJobsTest($jobIDList)
    {
        $objects = $this->objectModel->getSuccessJobs($jobIDList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBuildUrlTest($jenkins)
    {
        $objects = $this->objectModel->getBuildUrl($jenkins);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createByJobTest($jobID, $data = '', $type = 'tag')
    {
        global $tester;
        $this->objectModel->createByJob($jobID, $data = '', $type = 'tag');

        $objects = $tester->dao->select('name')->from(TABLE_COMPILE)->where($type)->eq($data)->fetchAll();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function execTest($compile)
    {
        $objects = $this->objectModel->exec($compile);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
