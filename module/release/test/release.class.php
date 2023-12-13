<?php
class releaseTest
{
    public function __construct()
    {
         global $tester, $app;
         $this->objectModel = $tester->loadModel('release');

         $app->rawModule = 'release';
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
     * 获取通知的人员。
     * Get notify persons.
     *
     * @param  int    $releaseID
     * @access public
     * @return array
     */
    public function getNotifyPersonsTest($releaseID): array
    {
        $release = $this->objectModel->getById($releaseID);
        return $this->objectModel->getNotifyPersons($release);
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
     * 批量解除发布跟Bug的关联。
     * Batch unlink bug.
     *
     * @param  int    $releaseID
     * @param  string $type      bug|leftBug
     * @param  array  $bugIdList
     * @access public
     * @return false|array
     */
    public function batchUnlinkBugTest(int $releaseID, string $type = 'bug', array $bugIdList = array()): false|array
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->batchUnlinkBug($releaseID, $type, $bugIdList);

        if(!$oldRelease) return false;
        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 激活/停止维护发布。
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status   normal|terminate
     * @access public
     * @return array
     */
    public function changeStatusTest(int $releaseID, string $status)
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->changeStatus($releaseID, $status);

        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->getByID($releaseID);
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 获取发送邮件的人员。
     * Get toList and ccList.
     *
     * @param  int         $releaseID
     * @access public
     * @return false|array
     */
    public function getToAndCcListTest($releaseID): false|array
    {
        $release = $this->objectModel->getByID($releaseID);
        return $this->objectModel->getToAndCcList($release);
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

    /**
     * 根据发布状态和权限生成列表中操作列按钮。
     * Build table action menu for release browse page.
     *
     * @param  int    $releaseID
     * @access public
     * @return array
     */
    public function buildActionListTest(int $releaseID): array
    {
        $release = $this->objectModel->getByID($releaseID);
        if(!$release) return array();

        return $this->objectModel->buildActionList($release);
    }

    /**
     * 构造发布详情页面的操作按钮。
     * Build release view action menu.
     *
     * @param  int    $releaseID
     * @access public
     * @return array
     */
    public function buildOperateViewMenuTest(int $releaseID): array
    {
        $release = $this->objectModel->getByID($releaseID);
        if(!$release) return array();

        return $this->objectModel->buildOperateViewMenu($release);
    }

    /**
     * 判断按钮是否可点击。
     * udge btn is clickable or not.
     *
     * @param  int    $releaseID
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(int $releaseID, string $action): bool
    {
        $release = $this->objectModel->getByID($releaseID);
        return $this->objectModel->isClickable($release, $action);
    }
}
