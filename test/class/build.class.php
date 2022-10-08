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

    /**
     * function getByList test by build
     *
     * @param  array  $idList
     * @param  string $count
     * @access public
     * @return array
     */
    public function getByListTest($idList, $count)
    {
        $objects = $this->objectModel->getByList($idList);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getProjectBuilds test by build
     *
     * @param  int    $count
     * @param  int    $projectID
     * @param  string $type
     * @param  string $param
     * @param  string $orderBy
     * @param  string $pager
     * @access public
     * @return array
     */
    public function getProjectBuildsTest($count, $projectID, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        $objects = $this->objectModel->getProjectBuilds($projectID, $type, $param, $orderBy, $pager);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getProjectBuildsBySearch test by build
     *
     * @param  string $count
     * @param  string $projectID
     * @param  string $queryID
     * @access public
     * @return array
     */
    public function getProjectBuildsBySearchTest($count, $projectID, $queryID)
    {
        $objects = $this->objectModel->getProjectBuildsBySearch($projectID, $queryID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getExecutionBuilds test by build
     *
     * @param  int    $count
     * @param  int    $executionID
     * @param  string $type
     * @param  string $param
     * @param  string $orderBy
     * @param  string $pager
     * @access public
     * @return array
     */
    public function getExecutionBuildsTest($count, $executionID, $type = '', $param = '', $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        $objects = $this->objectModel->getExecutionBuilds($executionID, $type, $param, $orderBy, $pager);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getExecutionBuildsBySearch test by build
     *
     * @param  int $count
     * @param  int $executionID
     * @param  int $queryID
     * @access public
     * @return arraty
     */
    public function getExecutionBuildsBySearchTest($count, $executionID, $queryID)
    {
        $objects = $this->objectModel->getExecutionBuildsBySearch($executionID, $queryID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);


        return $objects;
    }

    /**
     * Function getBuildPairs test by build
     *
     * @param  int    $count
     * @param  array  $products
     * @param  string $branch
     * @param  string $params
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $buildIdList
     * @param  string $replace
     * @access public
     * @return array
     */
    public function getBuildPairsTest($count, $products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true)
    {
        $objects = $this->objectModel->getBuildPairs($products, $branch, $params, $objectID, $objectType, $buildIdList, $replace);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * Function getLast test by build
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getLastTest($executionID)
    {
        $objects = $this->objectModel->getLast($executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function create test by build
     *
     * @param  int   $executionID
     * @param  array $param
     * @access public
     * @return array
     */
    public function createTest($executionID, $param = array())
    {
        $toData = date('Y-m-d');
        $labels = array();
        $files  = array();

        $createFields = array('product' => '', 'name' => '', 'builder' => 'admin', 'date' => $toData, 'scmPath' => '', 'filePath' => '',
            'labels' => $labels, 'files' => $files, 'desc' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create($executionID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($objectID);
        return $objects;
    }

    /**
     * Function update test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateTest($buildID, $param = array())
    {
        $toData = date('Y-m-d');
        $labels = array();
        $files  = array();

        $createFields = array('name' => '', 'builder' => 'admin', 'date' => $toData, 'scmPath' => '', 'filePath' => '',
            'labels' => $labels, 'files' => $files, 'desc' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->update($buildID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function updateLinkedBug test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateLinkedBugTest($buildID, $param = array())
    {
        global $tester;

        $build = $this->objectModel->getByID($buildID);
        $bugs  = array();

        $createFields = array('bugs' => $bugs);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $build->bugs .= ',' . join(',', $_POST['bugs']);

        $this->objectModel->updateLinkedBug($build);

        $objects = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->in(join(',', $_POST['bugs']))->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function linkStory test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function linkStoryTest($buildID, $param = array())
    {
        global $tester;

        $stories = array();

        $createFields = array('stories' => $stories);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->linkStory($buildID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function unlinkStory test by build
     *
     * @param  int   $buildID
     * @param  array $stories
     * @param  int  $storyID
     * @access public
     * @return array
     */
    public function unlinkStoryTest($buildID, $stories = array(), $storyID = 0)
    {
        global $tester;

        $createFields = array('stories' => $stories);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        $this->objectModel->linkStory($buildID);
        $this->objectModel->unlinkStory($buildID, $storyID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Functtion batchUnlinkStory test by build
     *
     * @param  int $buildID
     * @param  array $stories
     * @access public
     * @return array
     */
    public function batchUnlinkStoryTest($buildID, $stories = array())
    {
        global $tester;

        $createFields = array('stories' => $stories);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->linkStory($buildID);

        unset($_POST);

        $newFields = array('unlinkStories' => $stories);
        foreach($newFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->batchUnlinkStory($buildID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function linkBug test by build
     *
     * @param  int $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function linkBugTest($buildID, $param = array())
    {
        global $tester;

        $bugs = array();

        $createFields = array('bugs' => $bugs);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->linkBug($buildID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function unlinkBug test by build
     *
     * @param  int $buildID
     * @param  array $bugs
     * @param  int $bugID
     * @access public
     * @return array
     */
    public function unlinkBugTest($buildID, $bugs, $bugID)
    {
        global $tester;

        $createFields = array('bugs' => $bugs);

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        $this->objectModel->linkBug($buildID);
        $this->objectModel->unlinkBug($buildID, $bugID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchUnlinkBugTest($buildID, $bugs)
    {
        global $tester;

        $createFields = array('bugs' => $bugs);
        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->linkBug($buildID);

        unset($_POST);

        $newFields = array('unlinkBugs' => $bugs);
        foreach($newFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        $this->objectModel->batchUnlinkBug($buildID);

        $objects = $tester->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
