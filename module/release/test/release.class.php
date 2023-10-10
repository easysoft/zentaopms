<?php
class releaseTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('release');
    }

    /**
     * function getByIDTest by release
     *
     * @param  string $releaseID
     * @param  bool   $setImgSize
     * @access public
     * @return array
     */
    public function getByIDTest($releaseID, $setImgSize = false)
    {
        $objects = $this->objectModel->getByID($releaseID, $setImgSize);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 创建一个发布。
     * Create a release.
     *
     * @param  array  $data
     * @param  bool   $isSync
     * @access public
     * @return array
     */

    public function createTest(array $data, bool $isSync)
    {
        $date = date('Y-m-d');
        $createFields = array('name' => '','marker' => '1', 'build' => '', 'date' => $date, 'desc' => '', 'mailto' => '', 'stories' => '', 'bugs' => '', 'createdBy' => 'admin', 'createdDate' => helper::now());

        $release = new stdclass();
        foreach($createFields as $field => $defaultValue) $release->$field = $defaultValue;
        foreach($data as $key => $value) $release->$key = $value;

        $objectID = $this->objectModel->create($release, $isSync);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($objectID);
        return $object;
    }
    /**
     * 编辑一个发布。
     * Update a release.
     *
     * @param  int               $releaseID
     * @param  array             $data
     * @access public
     * @return array|object|false
     */
    public function updateTest(int $releaseID, array $data = array()): array|object|false
    {
        $release = new stdclass();
        foreach($data as $key => $value) $release->$key = $value;
        $oldRelease = $this->objectModel->getByID($releaseID);
        if(!$oldRelease) return false;

        $this->objectModel->update($release, $oldRelease);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($releaseID);
    }

    /**
     * Get notify persons.
     *
     * @param  string $notfiyList
     * @param  int    $productID
     * @param  int    $buildID
     * @param  int    $releaseID
     * @access public
     * @return array
     */

    public function getNotifyPersonsTest($releaseID)
    {
        $release = $this->objectModel->getById($releaseID);
        $objects = $this->objectModel->getNotifyPersons($release);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 发布批量关联需求。
     * Link stories to a release.
     *
     * @param  int         $releaseID
     * @param  array       $stories
     * @access public
     * @return false|array
     */
    public function linkStoryTest(int $releaseID, array $stories): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->linkStory($releaseID, $stories);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 移除关联的需求。
     * Unlink a story.
     *
     * @param  int         $releaseID
     * @param  int         $storyID
     * @access public
     * @return false|array
     */
    public function unlinkStoryTest(int $releaseID, int $storyID): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->unlinkStory($releaseID, $storyID);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 批量解除发布跟需求的关联。
     * Batch unlink story.
     *
     * @param  int         $releaseID
     * @param  array       $storyIdList
     * @access public
     * @return false|array
     */
    public function batchUnlinkStoryTest(int $releaseID, array $storyIdList): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->batchUnlinkStory($releaseID, $storyIdList);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 发布批量关联Bug。
     * Link bugs.
     *
     * @param  int         $releaseID
     * @param  string      $type      bug|leftBug
     * @param  array       $bugs
     * @access public
     * @return false|array
     */

    public function linkBugTest(int $releaseID, string $type = 'bug', array $bugs = array()): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->linkBug($releaseID, $type, $bugs);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 移除关联的Bug。
     * Unlink bug.
     *
     * @param  int         $releaseID
     * @param  int         $bugID
     * @param  string      $type
     * @access public
     * @return false|array
     */
    public function unlinkBugTest(int $releaseID, int $bugID, string $type = 'bug'): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->unlinkBug($releaseID, $bugID, $type);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * Batch unlink bug.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @param  string $bugs
     * @access public
     * @return array
     */
    public function batchUnlinkBugTest($releaseID, $type = 'bug', $bugs = '')
    {
        $_POST['bugs'] = $bugs;
        $this->objectModel->linkBug($releaseID);
        unset($_POST);

        $_POST['unlinkBugs'] = $bugs;
        $this->objectModel->batchUnlinkBug($releaseID, $type);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($releaseID);
        return $objects;
    }

    /**
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status
     * @access public
     * @return bool
     */

    public function changeStatusTest($releaseID, $status)
    {
        $this->objectModel->changeStatus($releaseID, $status);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID($releaseID);
        return $objects;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $release
     * @access public
     * @return bool|array
     */
    public function getToAndCcListTest($releaseID)
    {
        $release = $this->objectModel->getByID($releaseID);

        $objects = $this->objectModel->getToAndCcList($release);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 处理待创建的发布字段。
     * Process release fields for create.
     *
     * @param  int         $releaseID
     * @param  bool        $isSync
     * @access public
     * @return object|false
     */
    public function processReleaseForCreateTest(int $releaseID, bool $isSync): object|false
    {
        $release = $this->objectModel->getByID($releaseID);
        if(!$release) return false;

        return $this->objectModel->processReleaseForCreate($release, $isSync);
    }

    /**
     * 删除发布。
     * Delete a release.
     *
     * @param  int          $releaseID
     * @access public
     * @return object|false
     */
    public function deleteTest(int $releaseID): object|false
    {
        $this->objectModel->delete(TABLE_RELEASE, $releaseID);
        return $this->objectModel->getByID($releaseID);
    }
}
