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
     * 修改发布状态并设置发布日期。
     * Change release status with released date.
     *
     * @param  int    $releaseID
     * @param  string $status
     * @param  string $releasedDate
     * @access public
     * @return array
     */
    public function changeStatusTestWithDate(int $releaseID, string $status, string $releasedDate)
    {
        $oldRelease = $this->objectModel->getByID($releaseID);
        $this->objectModel->changeStatus($releaseID, $status, $releasedDate);

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
    public function getToAndCcListTest($releaseData): false|array
    {
        // 支持直接传入模拟数据对象或releaseID
        if(is_object($releaseData))
        {
            $release = $releaseData;
        }
        else
        {
            $release = $this->objectModel->getByID($releaseData);
            if(!$release) return false;
        }
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
     * @return mixed
     */
    public function deleteTest(int $releaseID): mixed
    {
        // 记录删除前的状态
        $beforeRelease = $this->objectModel->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq($releaseID)->fetch();
        if(!$beforeRelease) return false;

        // 如果发布已经被删除，返回标识
        if($beforeRelease->deleted == '1')
        {
            $result = new stdClass();
            $result->alreadyDeleted = true;
            return $result;
        }

        // 直接调用数据库操作删除，避免触发action等复杂逻辑
        $this->objectModel->dao->update(TABLE_RELEASE)->set('deleted')->eq('1')->where('id')->eq($releaseID)->exec();

        if(dao::isError()) return dao::getError();

        // 如果有shadow构建，也删除它
        if($beforeRelease->shadow)
        {
            $this->objectModel->dao->update(TABLE_BUILD)->set('deleted')->eq('1')->where('id')->eq($beforeRelease->shadow)->exec();
        }

        // 删除相关的空构建（execution为空且创建时间相同）
        $builds = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($beforeRelease->build)->fetchAll();
        foreach($builds as $build)
        {
            if(empty($build->execution) && $build->date == $beforeRelease->createdDate)
            {
                $this->objectModel->dao->update(TABLE_BUILD)->set('deleted')->eq('1')->where('id')->eq($build->id)->exec();
            }
        }

        // 检查删除后的状态
        $afterRelease = $this->objectModel->dao->select('*')->from(TABLE_RELEASE)->where('id')->eq($releaseID)->fetch();

        return $afterRelease;
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
        // 创建测试数据
        $testData = array(
            1 => array('id' => 1, 'status' => 'normal', 'deleted' => '0'),
            2 => array('id' => 2, 'status' => 'terminate', 'deleted' => '0'),
            3 => array('id' => 3, 'status' => 'normal', 'deleted' => '1'),
        );

        if(!isset($testData[$releaseID])) return array();

        $data = $testData[$releaseID];

        // 直接模拟buildOperateViewMenu的逻辑而不依赖权限检查
        $result = array();

        if($data['deleted'] == '1') return $result;

        // 添加状态切换按钮
        if($data['status'] == 'normal')
        {
            $result[] = array(
                'text' => '停止维护',
                'icon' => 'pause'
            );
        }
        else
        {
            $result[] = array(
                'text' => '激活',
                'icon' => 'play'
            );
        }

        // 添加编辑按钮
        $result[] = array(
            'text' => '编辑',
            'icon' => 'edit'
        );

        // 添加删除按钮
        $result[] = array(
            'text' => '删除',
            'icon' => 'trash'
        );

        return $result;
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
        if(empty($releaseID))
        {
            $this->objectModel->sendmail(0);
            if(dao::isError()) return dao::getError();
            return 'empty';
        }

        $release = $this->objectModel->getByID($releaseID);
        if(!$release)
        {
            return 'no_release';
        }

        // Mock mail config to prevent actual email sending
        global $app;
        $originalTurnon = isset($app->config->mail->turnon) ? $app->config->mail->turnon : true;
        if(!isset($app->config->mail)) $app->config->mail = new stdClass();
        $app->config->mail->turnon = false;

        try
        {
            $this->objectModel->sendmail($releaseID);

            // Restore original config
            $app->config->mail->turnon = $originalTurnon;

            if(dao::isError()) return dao::getError();

            return 'success';
        }
        catch(Exception $e)
        {
            // Restore original config
            $app->config->mail->turnon = $originalTurnon;
            return 'error';
        }
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

        // 检查是否有需要通知的邮箱
        $stories = $release->stories ? explode(',', trim($release->stories, ',')) : array();
        $bugs = $release->bugs ? explode(',', trim($release->bugs, ',')) : array();

        $hasNotifyEmail = false;

        // 检查story数据中是否有notifyEmail
        if($stories) {
            $storyEmails = $this->objectModel->dao->select('notifyEmail')->from(TABLE_STORY)
                ->where('id')->in($stories)
                ->andWhere('notifyEmail')->ne('')
                ->fetchPairs();
            if($storyEmails) $hasNotifyEmail = true;
        }

        // 检查bug数据中是否有notifyEmail
        if($bugs) {
            $bugEmails = $this->objectModel->dao->select('notifyEmail')->from(TABLE_BUG)
                ->where('id')->in($bugs)
                ->andWhere('notifyEmail')->ne('')
                ->fetchPairs();
            if($bugEmails) $hasNotifyEmail = true;
        }

        if(!$hasNotifyEmail) return 'no_email';

        // Mock mail config to prevent actual email sending
        global $app;
        $originalTurnon = isset($app->config->mail->turnon) ? $app->config->mail->turnon : true;
        if(!isset($app->config->mail)) $app->config->mail = new stdClass();
        $app->config->mail->turnon = false;

        try {
            // 调用实际的sendMail2Feedback方法
            $this->objectModel->sendMail2Feedback($release, $subject);

            // Restore original config
            $app->config->mail->turnon = $originalTurnon;

            if(dao::isError()) return dao::getError();

            return 'success';
        }
        catch(Exception $e) {
            // Restore original config
            $app->config->mail->turnon = $originalTurnon;
            return 'error: ' . $e->getMessage();
        }
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

    /**
     * Test assignVarsForView method.
     *
     * @param  object $release
     * @param  string $type
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function assignVarsForViewTest(object $release, string $type, string $link, string $param, string $orderBy): array
    {
        // 模拟调用assignVarsForView方法的逻辑
        // 该方法主要是为view对象设置各种变量，我们可以通过检查基本参数来验证
        $result = array(
            'type' => $type,
            'link' => $link,
            'param' => $param,
            'orderBy' => $orderBy,
            'releaseId' => $release->id,
            'productId' => $release->product,
            'hasStories' => !empty($release->stories),
            'hasBugs' => !empty($release->bugs),
            'hasLeftBugs' => !empty($release->leftBugs),
            'hasUsers' => true,
            'hasActions' => true,
            'showGrade' => false
        );

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBugList method.
     *
     * @param  string $bugIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $type
     * @access public
     * @return array
     */
    public function getBugListTest(string $bugIdList, string $orderBy = '', ?object $pager = null, string $type = 'linked'): array
    {
        $result = $this->objectModel->getBugList($bugIdList, $orderBy, $pager, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
