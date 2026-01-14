<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class actionTaoTest extends baseTest
{
    protected $moduleName = 'action';
    protected $className  = 'tao';

    /**
     * Test processLinkStoryAndBugActionExtra method.
     *
     * @param  string $ids
     * @param  string $module
     * @param  string $method
     * @access public
     * @return object
     */
    public function processLinkStoryAndBugActionExtraTest(string $ids, string $module = 'story', string $method = 'view'): object
    {
        $action = new stdClass();
        $action->extra = $ids;

        $this->instance->processLinkStoryAndBugActionExtra($action, $module, $method);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processToStoryActionExtra method.
     *
     * @param  int    $storyID
     * @param  string $product
     * @access public
     * @return object
     */
    public function processToStoryActionExtraTest(int $storyID, string $product = '1'): object
    {
        $action = new stdClass();
        $action->extra = (string)$storyID;
        $action->product = $product;

        $this->instance->processToStoryActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processCreateChildrenActionExtra method.
     *
     * @param  string $taskIDs
     * @access public
     * @return object
     */
    public function processCreateChildrenActionExtraTest(string $taskIDs): object
    {
        $action = new stdClass();
        $action->extra = $taskIDs;

        $this->instance->processCreateChildrenActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processAttribute method.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function processAttributeTest(string $type): string
    {
        $result = $this->instance->processAttribute($type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLinkedExtra method.
     *
     * @param  object $action
     * @param  string $type
     * @access public
     * @return bool
     */
    public function getLinkedExtraTest(object $action, string $type): bool
    {
        $result = $this->instance->getLinkedExtra($action, $type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processActionExtra method.
     *
     * @param  string $table
     * @param  int    $extraID
     * @param  string $fields
     * @param  string $type
     * @param  string $method
     * @param  bool   $onlyBody
     * @param  bool   $addLink
     * @access public
     * @return string
     */
    public function processActionExtraTest(string $table, int $extraID, string $fields, string $type, string $method = 'view', bool $onlyBody = false, bool $addLink = true): string
    {
        // 创建测试用的action对象
        $action = new stdClass();
        $action->extra      = $extraID;
        $action->objectType = 'bug';
        $action->action     = 'converttotask';
        $action->project    = 1;

        $originalExtra = $action->extra; // 备份原始extra值

        // 模拟onlyBody场景
        if($onlyBody)
        {
            $originalIsonlybody = $this->instance->config->requestType ?? 'GET';
            $_GET['onlybody'] = 'yes';
        }

        $this->instance->processActionExtra($table, $action, $fields, $type, $method, $onlyBody, $addLink);
        if(dao::isError()) return dao::getError();

        if($onlyBody) unset($_GET['onlybody']); // 恢复onlyBody状态

        // 分析结果 - 优先检查特殊场景
        if($action->extra == $originalExtra) return 'no_change';
        if($onlyBody && strpos($action->extra, '<a') === false) return 'onlybody_mode';
        if(!$addLink && strpos($action->extra, '#') !== false && strpos($action->extra, '<a') === false) return 'no_link';
        if($action->objectType == 'bug' && $type == 'task' && strpos($action->extra, 'data-app=') !== false) return 'bug_to_task';
        if(strpos($action->extra, '<a') !== false) return 'contains_link';
        return 'processed';
    }

    /**
     * Test processStoryGradeActionExtra method.
     *
     * @param  int $storyID
     * @access public
     * @return object
     */
    public function processStoryGradeActionExtraTest(int $storyID): object
    {
        // 创建测试用的action对象
        $action = new stdClass();
        $action->objectID = $storyID;
        $action->extra    = '';

        $result = $this->instance->processStoryGradeActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processParamString method.
     *
     * @param  object $action
     * @param  string $type
     * @access public
     * @return string
     */
    public function processParamStringTest(object $action, string $type): string
    {
        $result = $this->instance->processParamString($action, $type);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processCreateChildrenActionExtra method.
     *
     * @param  string $taskIds
     * @access public
     * @return object
     */
    public function processCreateChildrenActionExtraTest(string $taskIds): object
    {
        // 创建模拟的action对象
        $action = new stdClass();
        $action->extra = $taskIds;

        $this->instance->processCreateChildrenActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processCreateRequirementsActionExtra method.
     *
     * @param  string $storyIds
     * @access public
     * @return object
     */
    public function processCreateRequirementsActionExtraTest(string $storyIds): object
    {
        // 创建模拟的action对象
        $action = new stdClass();
        $action->extra = $storyIds;

        $this->instance->processCreateRequirementsActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processAppendLinkByExtra method.
     *
     * @param  string $extra
     * @param  string $objectType
     * @access public
     * @return object
     */
    public function processAppendLinkByExtraTest(string $extra, string $objectType = 'task'): object
    {
        $action = new stdClass();
        $action->extra      = $extra;
        $action->objectType = $objectType;

        $this->instance->processAppendLinkByExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processLinkStoryAndBugActionExtra method.
     *
     * @param  string $extra
     * @param  string $module
     * @param  string $method
     * @access public
     * @return object
     */
    public function processLinkStoryAndBugActionExtraTest(string $extra, string $module, string $method): object
    {
        $action = new stdClass();
        $action->extra = $extra;

        $this->instance->processLinkStoryAndBugActionExtra($action, $module, $method);
        return $action;
    }

    /**
     * Test processToStoryActionExtra method.
     *
     * @param  object $action
     * @access public
     * @return object
     */
    public function processToStoryActionExtraTest(object $action): object
    {
        $this->instance->processToStoryActionExtra($action);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test getActionTable method.
     *
     * @param  string $period
     * @access public
     * @return string
     */
    public function getActionTableTest(string $period): string
    {
        $result = $this->instance->getActionTable($period);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test addObjectNameForAction method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  array  $objectNames
     * @param  string $actionType
     * @param  string $extra
     * @access public
     * @return object
     */
    public function addObjectNameForActionTest(string $objectType, int $objectID, array $objectNames, string $actionType = '', string $extra = ''): object
    {
        // 创建测试用的action对象
        $action = new stdClass();
        $action->objectType = $objectType;
        $action->objectID   = $objectID;
        $action->action     = $actionType;
        $action->extra      = $extra;
        $action->objectName = '';

        $this->instance->addObjectNameForAction($action, $objectNames, $objectType);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test processMaxDocObjectLink method.
     *
     * @param  object $action
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $vars
     * @access public
     * @return object
     */
    public function processMaxDocObjectLinkTest(object $action, string $moduleName, string $methodName, string $vars): object
    {
        $this->instance->processMaxDocObjectLink($action, $moduleName, $methodName, $vars);
        if(dao::isError()) return dao::getError();
        return $action;
    }

    /**
     * Test getDocLibLinkParameters method.
     *
     * @param  object $action
     * @access public
     * @return array|bool
     */
    public function getDocLibLinkParametersTest(object $action)
    {
        $result = $this->instance->getDocLibLinkParameters($action);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDoclibTypeParams method.
     *
     * @param  object $action
     * @access public
     * @return array
     */
    public function getDoclibTypeParamsTest(object $action): array
    {
        $result = $this->instance->getDoclibTypeParams($action);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getObjectLinkParams method.
     *
     * @param  object $action
     * @param  string $vars
     * @access public
     * @return string
     */
    public function getObjectLinkParamsTest(object $action, string $vars): string
    {
        $result = $this->instance->getObjectLinkParams($action, $vars);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getReleaseRelated method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getReleaseRelated(string $objectType, int $objectID): array
    {
        $result = $this->instance->getReleaseRelated($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getReviewRelated method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getReviewRelatedTest(string $objectType, int $objectID): array
    {
        $result = $this->instance->getReviewRelated($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getNeedRelatedFields method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getNeedRelatedFieldsTest(string $objectType, int $objectID, string $actionType = '', string $extra = ''): array
    {
        $result = $this->instance->getNeedRelatedFields($objectType, $objectID, $actionType, $extra);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getGenerateRelated method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getGenerateRelatedTest(string $objectType, int $objectID): array
    {
        $result = $this->instance->getGenerateRelated($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getTaskRelated method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getTaskRelated(string $objectType, int $objectID): array
    {
        $result = $this->instance->getTaskRelated($objectType, $objectID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 根据类型和ID获取操作记录列表。
     * Get action list by type and ID.
     *
     * @param  string    $objectType
     * @param  int|array $objectID
     * @param  array     $modules
     * @access public
     * @return array
     */
    public function getActionListByTypeAndIDTest(string $objectType, int|array $objectID, array $modules): array
    {
        $result = $this->instance->getActionListByTypeAndID($objectType, $objectID, $modules);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
