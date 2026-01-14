<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class actionModelTest extends baseTest
{
    protected $moduleName = 'action';
    protected $className  = 'model';

    /**
     * Test create a action.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $comment
     * @param  string $extra
     * @param  string $actor
     * @param  bool   $autoDelete
     * @access public
     * @return object
     */
    public function createTest($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $uid = '', string $version = '')
    {
        if($this->instance->app->upgrading && !empty($version))
        {
            $this->instance->dao->update(TABLE_CONFIG)->set('value')->eq($version)->where('`key`')->eq('version')->andWhere('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('global')->exec();
        }

        $_SERVER['HTTP_HOST'] = 'pms.zentao.com';
        if($uid)
        {
            $_POST['uid'] = $uid;
            $this->instance->session->set('album', null);
            $this->instance->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $actionID = $this->instance->create($objectType, $objectID, $actionType, $comment, $extra, $actor);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $actionID ? $this->instance->getById($actionID) : 0;
    }

    /**
     * Test update read field of action when view a task/bug.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function readTest($objectType, $objectID)
    {
        $this->instance->read($objectType, $objectID);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll();
    }

    /**
     * Get the unread actions.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function getUnreadActionsTest($actionID = 0)
    {
        $objects = $this->instance->getUnreadActions($actionID);
        $objects = json_decode($objects);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 测试获取对象的产品、项目、执行。
     * Test get product, project, execution of the object.
     *
     * @param string $objectType
     * @param int    $objectID
     * @param string $actionType
     * @param string $extra
     * @access public
     * @return array
     */
    public function getRelatedFieldsTest(string $objectType, int $objectID, string $actionType = '', string $extra = ''): array
    {
        $objects = $this->instance->getRelatedFields($objectType, $objectID, $actionType, $extra);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get actions of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $edition
     * @access public
     * @return string
     */
    public function getListTest(string $objectType, int $objectID): string
    {
        $objects = $this->instance->getList($objectType, $objectID);
        $modules = $objectType == 'module' ? $this->instance->dao->select('id')->from(TABLE_MODULE)->where('root')->in($objectID)->fetchPairs('id') : array();
        $actions = $this->instance->getActionListByTypeAndID($objectType, $objectID, $modules);
        if(dao::isError()) return dao::getError();

        $actionID = key($actions);

        if(isset($objects[$actionID]->appendLink)) return 'link';

        if($objects[$actionID]->extra == $actions[$actionID]->extra) return 'nochanged';

        $dirname  = dirname(__DIR__) . DS;
        $newExtra = str_replace($dirname, '', $objects[$actionID]->extra);
        $newExtra = trim($newExtra, "\n");
        $newExtra = strpos($newExtra, 'href') !== false ? 'link' : 'title';

        return $newExtra;
    }

    /**
     * 测试将项目日志类型转换为动态类型。
     * Test process Project Actions change actionStype.
     *
     * @param  mixed  $actionIdList
     * @access public
     * @return object|string
     */
    public function processProjectActionsTest($actionIdList)
    {
        if(empty($actionIdList)) return [];

        $actions = $this->instance->dao->select('objectType, action')->from(TABLE_ACTION)->where('id')->in($actionIdList)->fetchAll();
        $result  = $this->instance->processProjectActions($actions);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test get action by id.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function getByIdTest($actionID)
    {
        $object = $this->instance->getById((int)$actionID);
        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * 测试通过搜索获取回收站内容。
     * Test get trashes by search.
     *
     * @param  string     $objectType
     * @param  string     $type all|hidden
     * @param  string|int $queryID
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getTrashesBySearchTest(string $objectType, string $type, string|int $queryID, string $orderBy, ?object $pager = null): array
    {
        $objects = $this->instance->getTrashesBySearch($objectType, $type, $queryID, $orderBy, $pager);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get deleted objects.
     *
     * @param  string $objectType
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTrashesTest($objectType, $type, $orderBy, $pager)
    {
        $objects = $this->instance->getTrashes($objectType, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get object type list of trashes.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTrashObjectTypesTest($type)
    {
        $objects = $this->instance->getTrashObjectTypes($type);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get histories of an action.
     *
     * @param  int|array $actionID
     * @access public
     * @return array
     */
    public function getHistoryTest(int|array $actionID): array
    {
        $result = $this->instance->getHistory($actionID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return array
     */
    public function logHistoryTest(int $actionID, array $changes): array
    {
        $result = $this->instance->logHistory($actionID, $changes);
        if(dao::isError()) return dao::getError();

        $histories = $this->instance->getHistory($actionID);
        return $histories[$actionID] ?? [];
    }

    /**
     * Get dynamic show action.
     *
     * @param  string $module
     * @access public
     * @return string
     */
    public function getActionConditionTest($module = '')
    {
        $objects = $this->instance->getActionCondition($module);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 测试通过查询条件获取动态
     * Test get dynamic by search.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $limit
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamicBySearchTest(int $queryID, string $orderBy = 'date_desc', int $limit = 50, string $date = '', string $direction = 'next')
    {
        if($queryID) $this->instance->session->set('actionQuery', null);

        if($date == 'today') $date = date('Y-m-d');
        $objects = $this->instance->getDynamicBySearch($queryID, $orderBy, $limit, $date, $direction);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get actions as dynamic.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $productID
     * @param  string $projectID
     * @param  string $executionID
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int
     */
    public function getDynamicTest($account = 'all', $period = 'all', $productID = 'all', $projectID = 'all', $executionID = 'all', $date = '', $direction = 'next')
    {
        if($date == 'today') $date = date('Y-m-d');
        $objects = $this->instance->getDynamic($account, $period, 'date_desc', 50, $productID, $projectID, $executionID, $date, $direction);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test getDynamiByProduct method.
     *
     * @param  int    $productID
     * @param  string $account
     * @param  string $period
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int|array
     */
    public function getDynamicByProductTest($productID, $account = '', $period = 'all', $date = '', $direction = 'next')
    {
        if($date == 'today') $date = date('Y-m-d');
        $objects = $this->instance->getDynamicByProduct((int)$productID, $account, $period, 'date_desc', 50, $date, $direction);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test getDynamiByProject method.
     *
     * @param  int    $projectID
     * @param  string $account
     * @param  string $period
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int|array
     */
    public function getDynamicByProjectTest($projectID, $account = 'all', $period = 'all', $orderBy = 'date_desc', $limit = 50, $date = '', $direction = 'next')
    {
        if($date == 'today') $date = date('Y-m-d');
        $objects = $this->instance->getDynamicByProject((int)$projectID, $account, $period, $orderBy, $limit, $date, $direction);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test get actions as dynamic by execution.
     *
     * @param  int    $executionID
     * @param  string $account
     * @param  string $period
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int|array
     */
    public function getDynamicByExecutionTest($executionID, $account = '', $period = 'all', $date = '', $direction = 'next')
    {
        $date = $date == 'today' ? date('Y-m-d', time()) : $date;
        $objects = $this->instance->getDynamicByExecution((int)$executionID, $account, $period, 'date_desc', 50, $date, $direction);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test get actions as dynamic by account.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return int|array
     */
    public function getDynamicByAccountTest($account = '', $period = 'all', $date = '', $direction = 'next')
    {
        if($date == 'today') $date = date('Y-m-d', time());
        $objects = $this->instance->getDynamicByAccount($account, $period, 'date_desc', 50, $date, $direction);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test get actions by SQL.
     *
     * @param  string $sql
     * @param  string $orderBy
     * @param  object  $pager
     * @access public
     * @return int
     */
    public function getBySQLTest(string $sql, string $orderBy = 'id_desc'): int
    {
        $objects = $this->instance->getBySQL($sql, $orderBy);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * Test transform the actions for display.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function transformActionsTest($actions)
    {
        $actions = $this->instance->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id', false);
        $objects = $this->instance->transformActions($actions);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get related data by actions.
     *
     * @param  array  $actions
     * @param  string $field
     * @access public
     * @return array
     */
    public function getRelatedDataByActionsTest($actions, $field)
    {
        $actions = $this->instance->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');
        list($objectNames, $relatedProjects, $requirements) = $this->instance->getRelatedDataByActions($actions);
        if(dao::isError()) return dao::getError();
        return ${$field};
    }

    /**
     * Test get object label.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  array  $requirements
     * @param  array  $epics
     * @access public
     * @return string
     */
    public function getObjectLabelTest($objectType, $objectID, $actionType, $requirements, $epics)
    {
        $object = $this->instance->getObjectLabel($objectType, $objectID, $actionType, $requirements, $epics);
        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * Test compute the begin date and end date of a period.
     *
     * @param  string $period
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function computeBeginAndEndTest($period, $date = '', $direction = 'next')
    {
        $this->instance->app->methodName = 'dynamic';
        $result = $this->instance->computeBeginAndEnd($period, $date, $direction);

        $today      = date('Y-m-d');
        $tomorrow   = date('Y-m-d', strtotime('+1 days'));
        $yesterday  = date('Y-m-d', strtotime('-1 days'));
        $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));

        if(!empty($date) && empty($direction)) return $result['begin'] == (date('Y') - 1) . '-01-01' and $result['end'] == '2030-01-01';

        if($direction == 'pre')      return $result['begin'] == '2025-04-23' && $result['end'] == '2030-01-01';
        if($direction == 'next')     return $result['begin'] == (date('Y') - 1) . '-01-01' && $result['end'] == '2025-04-23';
        if($period == 'all')         return $result['begin'] == (date('Y') - 1) . '-01-01' and $result['end'] == '2030-01-01';
        if($period == 'today')       return $result['begin'] == $today and $result['end'] == $tomorrow;
        if($period == 'yesterday')   return $result['begin'] == $yesterday and $result['end'] == $today;
        if($period == 'twodaysago')  return $result['begin'] == $twoDaysAgo and $result['end'] == $yesterday;
        if($period == 'latest3days') return $result['begin'] == $twoDaysAgo and $result['end'] == $tomorrow;
        if($period == 'thismonth')   return $result == date::getThisMonth();
        if($period == 'lastmonth')   return $result == date::getLastMonth();
        $func = "get$period";
        extract(date::$func());
        if($period == 'thisweek')    return $result['begin'] == $begin && $result['end'] == $end;
        if($period == 'lastweek')    return $result['begin'] == $begin && $result['end'] == $end;
    }

    /**
     * Delete action by objectType.
     *
     * @param  string    $objectType
     * @access public
     * @return bool
     */
    public function deleteByTypeTest($objectType)
    {
        $this->instance->deleteByType($objectType);
        if(dao::isError()) return dao::getError();

        $objects = $this->instance->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($objectType)->fetchAll();
        return empty($objects);
    }

    /**
     * 测试恢复一条记录。
     * Test undelete a record.
     *
     * @param  int    $actionID
     * @access public
     * @return object|bool
     */
    public function undeleteTest(int $actionID): string|bool
    {
        $result = $this->instance->undelete($actionID);
        if(dao::isError()) return dao::getError();
        if(is_string($result) || !$result) return $result;

        $action = $this->instance->getByID($actionID);
        $table  = $this->instance->config->objectTables[$action->objectType];
        $object = $this->instance->dao->select('deleted')->from($table)->where('id')->eq($action->objectID)->fetch();
        return $object->deleted == 0;
    }

    /**
     * Test hide an object.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function hideOneTest($actionID)
    {
        $this->instance->hideOne($actionID);
        if(dao::isError()) return dao::getError();
        return $this->instance->getByID($actionID);
    }

    /**
     * Test hide all deleted objects.
     *
     * @access public
     * @return array
     */
    public function hideAllTest()
    {
        $this->instance->hideAll();
        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('id,extra')->from(TABLE_ACTION)->where('action')->eq('deleted')->fetchAll();
    }

    /**
     * Test update comment of a action.
     *
     * @param  int    $actionID

     */
    public function updateCommentTest(int $actionID, string $comment, string $uid = '')
    {
        if($uid)
        {
            $_POST['uid'] = $uid;
            $this->instance->session->set('album', null);
            $this->instance->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $action = new stdclass();
        $action->lastComment = $comment;
        $action->uid         = $uid;
        $action->deleteFiles = array();
        $action->renameFiles = array();

        $this->instance->updateComment($actionID, $action);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->instance->getByID($actionID);
    }

    /**
     * 检查是否有上一条或下一条动态。
     * Check Has pre or next.
     *
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return bool
     */
    public function hasPreOrNextTest(string $date, string $direction = 'next'): bool
    {
        $this->instance->session->set('actionQueryCondition', '1=1');
        if($date == 'today') $date = date('Y-m-d');
        $result = $this->instance->hasPreOrNext($date, $direction);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Save global search object index information.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return bool
     */
    public function saveIndexTest(string $objectType, int $objectID, string $actionType, string $comment = '')
    {
        if($comment) $_POST['comment'] = $comment;
        $result = $this->instance->saveIndex($objectType, $objectID, $actionType);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Process dynamic for API.
     *
     * @param  array  $dynamics
     * @access public
     * @return array
     */
    public function processDynamicForAPITest($dynamics)
    {
        return $this->instance->processDynamicForAPI($dynamics);
    }

    /**
     * 测试搭建动态的日期分组。
     * Test build date group by actions.
     *
     * @param  array  $actions
     * @param  string $direction
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function buildDateGroupTest(array $actions, string $direction = 'next', string $orderBy = 'date_desc'): array
    {
        $objects = $this->instance->buildDateGroup($actions, $direction, $orderBy);
        if(dao::isError()) return dao::getError();

        if(!$objects) $objects = array();
        return array('dateCount' => count($objects), 'dateActions' => count($objects, COUNT_RECURSIVE) - count($objects));
    }

    /**
     * Test restore stages.
     *
     * @param  array    $stageList
     * @param  array    $actionIdList
     * @access public
     * @return object
     */
    public function restoreStagesTest($stageList, $actionIdList)
    {
        $this->instance->restoreStages($stageList);
        if(dao::isError()) return dao::getError();

        $actionList = array();
        foreach($actionIdList as $actionID) $actionList[$actionID] = $this->instance->getByID($actionID);
        foreach($stageList as $stageId)
        {
            $stage = $this->instance->dao->select('id, deleted')->from(TABLE_STAGE)->where('id')->eq($stageId)->fetch();
            if(!$stage || $stage->deleted) return false;
        }

        if(isset($actionList[2]) && $actionList[2]->extra == '0') return $actionList;

        return false;
    }

    /**
     * 测试获取重复的对象。
     * Test get repeat object.
     *
     * @param  object $object
     * @param  string $table
     * @access public
     * @return object|string
     */
    public function getRepeatObjectTest(int $actionID, string $table): object|string
    {
        $action = $this->instance->getById($actionID);
        $result = $this->instance->getRepeatObject($action, $table);
        if(dao::isError()) return dao::getError();
        return $result[0];
    }

    /**
     * 测试更新阶段的属性。
     * Test update stage attribute.
     *
     * @param  string     $attributeList
     * @param  array      $executionID
     * @access public
     * @return array|bool
     */
    public function updateStageAttributeTest(string $attribute, array $stages): array|bool
    {
        $this->instance->updateStageAttribute($attribute, $stages);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stages)->fetchAll();
    }

    /**
     * 通过id列表获取被删除的阶段。
     * Get deleted stages by id list.
     *
     * @param  array  $stages
     * @access public
     * @return array
     */
    public function getDeletedStagedByListTest(array $list): array
    {
        $objects = $this->instance->getDeletedStagedByList($list);
        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 通过id更新对象。
     * Update object by id.
     *
     * @param  string $table
     * @param  int    $id
     * @param  array  $updateParams
     * @access public
     * @return object
     */
    public function updateObjectByIDTest(string $table, int $id, array $updateParams)
    {
        $this->instance->updateObjectByID($table, $id, $updateParams);
        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from($table)->where('id')->eq($id)->fetch();
    }

    /**
     * 测试获取近似的对象。
     * Test get like object.
     *
     * @param  string $table
     * @param  string $field
     * @param  string $param
     * @param  string $value
     * @access public
     * @return int
     */
    public function getLikeObjectTest(string $table, string $field, string $param, string $value): int
    {
        $objects = $this->instance->getLikeObject($table, $field, $param, $value);
        if(dao::isError()) return 0;
        return count($objects);
    }

    /**
     * 测试获取第一条动态。
     * Test get first action.
     *
     * @access public
     * @return array|object|false
     */
    public function getFirstActionTest(): array|object|false
    {
        $object = $this->instance->getFirstAction();
        if(dao::isError()) return dao::getError();
        return $object;
    }

    /**
     * 将类型、状态等键值转换为具体的值。
     * Process object type, status and etc.
     *
     * @param  int    $historyID
     * @access public
     * @return object
     */
    public function processHistoryTest(int $historyID): object
    {
        $history = $this->instance->dao->select('*')->from(TABLE_HISTORY)->where('id')->eq($historyID)->fetch();
        return $this->instance->processHistory($history);
    }

    /**
     * 渲染每一个action的历史记录。
     * Render histories of every action.
     *
     * @param  string $objectType
     * @param  int    $historyID
     * @access public
     * @return string
     */
    public function renderChangesTest(string $objectType, int $historyID = 0): string
    {
        $histories = $this->instance->dao->select('*')->from(TABLE_HISTORY)->where('id')->eq($historyID)->fetchAll('id', false);
        $content   = $this->instance->renderChanges($objectType, 0, $histories);
        $content   = str_replace("\n", '', $content);
        return $content;
    }

    /**
     * 测试操作是否可点击。
     * Test check action clickable.
     *
     * @param  object $action
     * @param  array  $deptUser
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return bool
     */
    public function checkActionClickableTest(object $action, array $deptUser, string $moduleName, string $methodName): bool
    {
        return $this->instance->checkActionClickable($action, $deptUser, $moduleName, $methodName);
    }

    /**
     * Test getAccountFirstAction method.
     *
     * @param  string $account
     * @access public
     * @return object
     */
    public function getAccountFirstActionTest(string $account): object|false
    {
        $result = $this->instance->getAccountFirstAction($account);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test renderAction method.
     *
     * @param  object      $action
     * @param  string|array $desc
     * @access public
     * @return mixed
     */
    public function renderActionTest(object $action, string|array $desc = '')
    {
        $result = $this->instance->renderAction($action, $desc);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test formatActionComment method.
     *
     * @param  string $comment
     * @access public
     * @return string
     */
    public function formatActionCommentTest(string $comment): string
    {
        $result = $this->instance->formatActionComment($comment);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test printAction method.
     *
     * @param  object      $action
     * @param  string|array $desc
     * @access public
     * @return string
     */
    public function printActionTest(object $action, string|array $desc = ''): string
    {
        ob_start();
        $this->instance->printAction($action, $desc);
        $output = ob_get_contents();
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return $output;
    }

    /**
     * Test printChanges method.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  array  $histories
     * @param  bool   $canChangeTag
     * @access public
     * @return string
     */
    public function printChangesTest(string $objectType, int $objectID, array $histories, bool $canChangeTag = true): string
    {
        ob_start();
        $this->instance->printChanges($objectType, $objectID, $histories, $canChangeTag);
        $output = ob_get_contents();
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return str_replace("\n", '', $output);
    }

    /**
     * Test printActionForGitLab method.
     *
     * @param  object $action
     * @access public
     * @return string|false
     */
    public function printActionForGitLabTest(object $action): string|false
    {
        ob_start();
        $this->instance->printActionForGitLab($action);
        $output = ob_get_contents();
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return $output;
    }

    /**
     * Test processActionForAPI method.
     *
     * @param  array|object $actions
     * @param  array|object $users
     * @param  array|object $objectLang
     * @access public
     * @return array
     */
    public function processActionForAPITest($actions, $users = array(), $objectLang = array()): array
    {
        $result = $this->instance->processActionForAPI($actions, $users, $objectLang);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processActionForAPI method specifically for history field mapping.
     *
     * @param  array|object $actions
     * @param  array|object $users
     * @param  array|object $objectLang
     * @access public
     * @return string
     */
    public function processActionForAPIHistoryTest($actions, $users = array(), $objectLang = array()): string
    {
        $result = $this->processActionForAPITest($actions, $users, $objectLang);
        if(empty($result) || !isset($result[0]->history) || !is_array($result[0]->history) || empty($result[0]->history)) {
            return '';
        }
        return $result[0]->history[0]->fieldName ?? '';
    }

    /**
     * Test buildTrashSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildTrashSearchFormTest(int $queryID, string $actionURL): array
    {
        $originalConfig = $this->instance->config->trash->search ?? null; // 备份原始配置

        $this->instance->buildTrashSearchForm($queryID, $actionURL);
        if(dao::isError()) return dao::getError();

        // 获取设置后的配置值
        $result = array(
            'actionURL' => $this->instance->config->trash->search['actionURL'] ?? '',
            'queryID'   => $this->instance->config->trash->search['queryID'] ?? 0
        );

        if($originalConfig !== null) $this->instance->config->trash->search = $originalConfig; // 恢复原始配置

        return $result;
    }

    /**
     * Test getAttributeByExecutionID method.
     *
     * @param  int $executionID
     * @access public
     * @return mixed
     */
    public function getAttributeByExecutionIDTest(int $executionID)
    {
        $result = $this->instance->getAttributeByExecutionID($executionID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getObjectTypeTeamParams method.
     *
     * @param  object $action
     * @access public
     * @return array
     */
    public function getObjectTypeTeamParamsTest($action)
    {
        $result = $this->invokeArgs('getObjectTypeTeamParams', [$action]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkActionCanUndelete method.
     *
     * @param  object $action
     * @param  object $object
     * @access public
     * @return string|bool
     */
    public function checkActionCanUndeleteTest($action, $object)
    {
        $result = $this->invokeArgs('checkActionCanUndelete', [$action, $object]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
