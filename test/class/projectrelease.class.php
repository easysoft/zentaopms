<?php
class projectreleaseTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('projectrelease');
    }

    /**
    * Test get release by id.
    *
    * @param  int    $releaseID
    * @access public
    * @return object
    */
    public function getByIDTest($releaseID)
    {
        $objects = $this->objectModel->getByID($releaseID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }

        return $objects;
    }

    /**
    * Test get list of releases.
    *
    * @param  int    $projectID
    * @param  string $type
    * @access public
    * @return array
    */
    public function getListTest($projectID, $type = 'all')
    {
        $objects = $this->objectModel->getList($projectID, $type);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }

        return $objects;
    }

    /**
    * Test get last release.
    *
    * @param  int    $projectID
    * @access public
    * @return object
    */
    public function getLastTest($projectID)
    {
        $objects = $this->objectModel->getLast($projectID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }

        return $objects;
    }

    /**
    * Test get released builds from project.
    *
    * @param  int    $projectID
    * @access public
    * @return array
    */
    public function getReleasedBuildsTest($projectID)
    {
        $objects = $this->objectModel->getReleasedBuilds($projectID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error[0];
        }
        if(count($objects) == 1)
        {
            return count($objects);
        }
        else
        {
            return $objects;
        }
    }

    /**
    * Test update a release.
    *
    * @param  int    $releaseID
    * @param  string $name
    * @param  string $date
    * @access public
    * @return array
    */
    public function updateTest($releaseID, $name = '', $date = '')
    {
        global $app;
        $app->loadConfig('release');

        $updateFields['name']    = $name;
        $updateFields['build']   = 1;
        $updateFields['date']    = $date;
        $updateFields['status']  = 'normal';
        $updateFields['desc']    = '';
        $updateFields['labels']  = array();
        $updateFields['files']   = array();
        $updateFields['uid']     = '62450877d0a27';
        $updateFields['product'] = 1;
        foreach($updateFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        $objects = $this->objectModel->update($releaseID);
        if($objects == array()) $objects = '没有数据更新';
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
    * Test link stories
    *
    * @param  int    $releaseID
    * @access public
    * @return array
    */
    public function linkStoryTest($releaseID)
    {
        $_POST['stories'] = array(1, 2);
        $this->objectModel->linkStory($releaseID);

        global $tester;
        $objects = $tester->dao->select('stories')->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
    * Test unlink stories
    *
    * @param  int    $releaseID
    * @param  int    $storyID
    * @access public
    * @return array
    */
    public function unlinkStoryTest($releaseID, $storyID)
    {
        $this->linkStoryTest($releaseID);
        $this->objectModel->unlinkStory($releaseID, $storyID);

        global $tester;
        $objects = $tester->dao->select('stories')->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
    * Test link bugs.
    *
    * @param  int    $releaseID
    * @param  string $type
    * @access public
    * @return array
    */
    public function linkBugTest($releaseID, $type = 'bug')
    {
        $_POST['bugs'] = array(1, 2);
        $this->objectModel->linkBug($releaseID, $type);

        global $tester;
        $field = $type == 'bug' ? 'bugs' : 'leftBugs';
        $objects = $tester->dao->select($field)->from(TABLE_RELEASE)->where('id')->eq((int)$releaseID)->fetch();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
