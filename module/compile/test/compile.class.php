<?php
class compileTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('compile');
    }

    /**
     * Get by id
     *
     * @param  int    $buildID
     * @access public
     * @return object
     */
    public function getByIDTest($buildID)
    {
        $objects = $this->objectModel->getByID($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get build list.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getListTest($repoID, $jobID, $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($repoID, $jobID, $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get list by jobID.
     *
     * @param  int $jobID
     * @return array
     */
    public function getListByJobIDTest($jobID)
    {
        $objects = $this->objectModel->getListByJobID($jobID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get last result.
     *
     * @param  int    $jobID
     * @access public
     * @return object
     */
    public function getLastResultTest($jobID)
    {
        $objects = $this->objectModel->getLastResult($jobID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get success jobs by job id list.
     *
     * @param  array  $jobIDList
     * @access public
     * @return array
     */
    public function getSuccessJobsTest($jobIDList)
    {
        $objects = $this->objectModel->getSuccessJobs($jobIDList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get build url.
     *
     * @param  object $jenkins
     * @access public
     * @return object
     */
    public function getBuildUrlTest($jenkins)
    {
        $objects = $this->objectModel->getBuildUrl($jenkins);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Save build by job
     *
     * @param  int    $jobID
     * @param  string $data
     * @param  string $type
     * @access public
     * @return void
     */
    public function createByJobTest($jobID, $data = '', $type = 'tag')
    {
        global $tester;
        $id = $this->objectModel->createByJob($jobID, $data = '', $type = 'tag');

        $objects = $tester->dao->select('name')->from(TABLE_COMPILE)->where('id')->eq($id)->fetch();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Execute compile
     *
     * @param  object $compile
     * @access public
     * @return bool
     */
    public function execTest($compile)
    {
        $objects = $this->objectModel->exec($compile);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
