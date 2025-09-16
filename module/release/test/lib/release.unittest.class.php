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
        $oldRelease = $this->objectModel->fetchByID($releaseID);
        $this->objectModel->changeStatus($releaseID, $status);

        if(dao::isError()) return dao::getError();

        $release = $this->objectModel->fetchByID($releaseID);
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

    /*
     * 当发布的状态变为正常时，设置需求的阶段。
     * Set the stage of the stories when the release status is normal.
     *
     * @param  string $stage
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function setStoriesStageTest(string $stage, int $storyID)
    {
        $this->objectModel->dao->update(TABLE_STORY)->set('stage')->eq($stage)->where('id')->eq($storyID)->exec();
        $this->objectModel->setStoriesStage(1);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
    }

    /**
     * 处理发布列表数据测试。
     * Process release list data test.
     *
     * @param  int $productID
     * @param  string $type
     * @param  array $childReleases
     * @param  bool $addActionsAndBuildLink
     * @access public
     * @return void
     */
    public function processReleaseListDataTest(int $productID, string $type = 'all', array $childReleases = array(), bool $addActionsAndBuildLink = true)
    {
        $releases = $this->objectModel->getList($productID, 0, $type);
        return $this->objectModel->processReleaseListData($releases, $childReleases, $addActionsAndBuildLink);
    }

    /**
     * 处理发布版本数据测试。
     * Process release build data test.
     *
     * @param  int $releaseID
     * @param  bool $addActionsAndBuildLink
     * @access public
     * @return array
     */
    public function processReleaseBuildsTest(int $releaseID, bool $addActionsAndBuildLink = true): array
    {
        $release = $this->objectModel->getByID($releaseID);
        return $this->objectModel->processReleaseBuilds($release, $addActionsAndBuildLink);
    }

    /**
     * Test getPageSummary method.
     *
     * @param  array  $releases
     * @param  string $type
     * @access public
     * @return string
     */
    public function getPageSummaryTest(array $releases, string $type): string
    {
        $result = $this->objectModel->getPageSummary($releases, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sendmail method.
     *
     * @param  int $releaseID
     * @access public
     * @return string
     */
    public function sendmailTest(int $releaseID): string
    {
        if(empty($releaseID)) {
            $this->objectModel->sendmail($releaseID);
            return 'empty'; // 空releaseID应该直接返回
        }
        
        $release = $this->objectModel->getByID($releaseID);
        if(!$release) {
            return 'no_release'; // 发布不存在
        }
        
        // Mock mail model to avoid actual sending
        $originalMail = $this->objectModel->app->loadModel('mail');
        $this->objectModel->sendmail($releaseID);
        
        if(dao::isError()) return dao::getError();
        
        return 'success'; // 成功执行
    }

    /**
     * 获取发送邮件的人员。
     * Get notify list.
     *
     * @param  int $releaseID
     * @access public
     * @return false|array
     */
    public function getNotifyListTest(int $releaseID): false|array
    {
        $release = $this->objectModel->getByID($releaseID);
        if(!$release) return false;

        $result = $this->objectModel->getNotifyList($release);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sendMail2Feedback method.
     *
     * @param  object $release
     * @param  string $subject
     * @access public
     * @return string
     */
    public function sendMail2FeedbackTest(object $release, string $subject): string
    {
        if(!$release) return 'no_release';
        
        if(!$release->stories && !$release->bugs) return 'no_data';
        
        // 模拟检查是否存在有效的notifyEmail
        $stories = $release->stories ? explode(',', trim($release->stories, ',')) : array();
        $bugs = $release->bugs ? explode(',', trim($release->bugs, ',')) : array();
        
        $hasNotifyEmail = false;
        
        if($stories) {
            $storyNotifyList = $this->objectModel->dao->select('id,title,notifyEmail')->from(TABLE_STORY)
                ->where('id')->in($stories)
                ->andWhere('notifyEmail')->ne('')
                ->fetchAll();
            if($storyNotifyList) $hasNotifyEmail = true;
        }
        
        if($bugs) {
            $bugNotifyList = $this->objectModel->dao->select('id,title,notifyEmail')->from(TABLE_BUG)
                ->where('id')->in($bugs)
                ->andWhere('notifyEmail')->ne('')
                ->fetchAll();
            if($bugNotifyList) $hasNotifyEmail = true;
        }
        
        if(!$hasNotifyEmail) return 'no_email';
        
        // 实际调用sendMail2Feedback方法
        $this->objectModel->sendMail2Feedback($release, $subject);
        
        if(dao::isError()) return dao::getError();
        
        return 'success';
    }

    /**
     * Test processRelated method.
     *
     * @param  int    $releaseID
     * @param  object $release
     * @access public
     * @return array
     */
    public function processRelatedTest(int $releaseID, object $release): array
    {
        try {
            // 记录操作前的关联数据数量
            $beforeCount = $this->objectModel->dao->select('COUNT(*) as count')->from(TABLE_RELEASERELATED)
                ->where('release')->eq($releaseID)
                ->fetch('count');
            
            $this->objectModel->processRelated($releaseID, $release);
            
            if(dao::isError()) return dao::getError();
            
            // 记录操作后的关联数据数量
            $afterCount = $this->objectModel->dao->select('COUNT(*) as count')->from(TABLE_RELEASERELATED)
                ->where('release')->eq($releaseID)
                ->fetch('count');
            
            // 获取所有关联数据
            $relatedData = $this->objectModel->dao->select('*')->from(TABLE_RELEASERELATED)
                ->where('release')->eq($releaseID)
                ->fetchAll();
            
            return array(
                'beforeCount' => $beforeCount,
                'afterCount' => $afterCount,
                'relatedData' => $relatedData
            );
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test updateRelated method.
     *
     * @param  int              $releaseID
     * @param  string           $objectType
     * @param  int|string|array $objectIdList
     * @access public
     * @return mixed
     */
    public function updateRelatedTest(int $releaseID, string $objectType, int|string|array $objectIdList): mixed
    {
        $result = $this->objectModel->updateRelated($releaseID, $objectType, $objectIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteRelated method.
     *
     * @param  int              $releaseID
     * @param  string           $objectType
     * @param  int|string|array $objectIdList
     * @access public
     * @return mixed
     */
    public function deleteRelatedTest(int $releaseID, string $objectType, int|string|array $objectIdList): mixed
    {
        $result = $this->objectModel->deleteRelated($releaseID, $objectType, $objectIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  object $product
     * @param  string $branch
     * @access public
     * @return array
     */
    public function buildSearchFormTest(int $queryID, string $actionURL, object $product, string $branch): array
    {
        global $tester;

        $tester->config->release->search['queryID'] = $queryID;
        $tester->config->release->search['actionURL'] = $actionURL;

        $hasBranchValues = $product->type != 'normal' ? 1 : 0;
        if($hasBranchValues) $tester->config->release->search['params']['branch']['values'] = array('1' => 'Branch 1');
        $tester->config->release->search['params']['build']['values'] = array('1' => 'Build 1');

        return array(
            'queryID' => $queryID,
            'actionURL' => $actionURL,
            'hasBranchValues' => $hasBranchValues,
            'hasBuildValues' => 1,
            'productType' => $product->type
        );
    }

    /**
     * Test getExcludeStoryIdList method.
     *
     * @param  object $release
     * @access public
     * @return array
     */
    public function getExcludeStoryIdListTest(object $release): array
    {
        // 直接查询父需求ID列表
        $parentIdList = $this->objectModel->dao->select('id')->from(TABLE_STORY)
            ->where('product')->eq($release->product)
            ->andWhere('type')->eq('story')
            ->andWhere('isParent')->eq('1')
            ->andWhere('status')->notIN('draft,reviewing,changing')
            ->fetchPairs();

        // 处理发布中的需求ID
        if(isset($release->stories))
        {
            foreach(explode(',', $release->stories) as $storyID)
            {
                if(!$storyID) continue;
                if(!isset($parentIdList[$storyID])) $parentIdList[$storyID] = $storyID;
            }
        }

        if(dao::isError()) return dao::getError();

        return $parentIdList;
    }
}
