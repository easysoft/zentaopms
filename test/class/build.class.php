<?php
class buildTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('build');
    }

    /**
     * function getByID by test
     *
     * @param  string $buildID
     * @param  beel   $setImgSize
     * @access public
     * @return object
     */
    public function getByIDTest($buildID, $setImgSize = false)
    {
        $objects = $this->objectModel->getByID($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByListTest($idList, $count)
    {
        $objects = $this->objectModel->getByList($idList);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    public function getProjectBuildsTest($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        $objects = $this->objectModel->getProjectBuilds($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectBuildsBySearchTest($projectID, $queryID)
    {
        $objects = $this->objectModel->getProjectBuildsBySearch($projectID, $queryID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionBuildsTest($executionID, $type = '', $param = '', $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        $objects = $this->objectModel->getExecutionBuilds($executionID, $type = '', $param = '', $orderBy = 't1.date_desc,t1.id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionBuildsBySearchTest($executionID, $queryID)
    {
        $objects = $this->objectModel->getExecutionBuildsBySearch($executionID, $queryID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBuildPairsTest($products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true)
    {
        $objects = $this->objectModel->getBuildPairs($products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getLastTest($executionID)
    {
        $objects = $this->objectModel->getLast($executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTest($executionID)
    {
        $objects = $this->objectModel->create($executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateTest($buildID)
    {
        $objects = $this->objectModel->update($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateLinkedBugTest($build)
    {
        $objects = $this->objectModel->updateLinkedBug($build);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function linkStoryTest($buildID)
    {
        $objects = $this->objectModel->linkStory($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function unlinkStoryTest($buildID, $storyID)
    {
        $objects = $this->objectModel->unlinkStory($buildID, $storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchUnlinkStoryTest($buildID)
    {
        $objects = $this->objectModel->batchUnlinkStory($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function linkBugTest($buildID)
    {
        $objects = $this->objectModel->linkBug($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function unlinkBugTest($buildID, $bugID)
    {
        $objects = $this->objectModel->unlinkBug($buildID, $bugID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchUnlinkBugTest($buildID)
    {
        $objects = $this->objectModel->batchUnlinkBug($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
