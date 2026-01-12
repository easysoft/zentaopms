<?php
declare(strict_types = 1);
class actionTest
{
    public $objectModel;
    public $objectTao;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('action');
        $this->objectTao   = $tester->loadTao('action');
    }

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
        if($this->objectModel->app->upgrading && !empty($version))
        {
            $this->objectModel->dao->update(TABLE_CONFIG)->set('value')->eq($version)->where('`key`')->eq('version')->andWhere('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('global')->exec();
        }

        $_SERVER['HTTP_HOST'] = 'pms.zentao.com';
        if($uid)
        {
            $_POST['uid'] = $uid;
            $this->objectModel->session->set('album', null);
            $this->objectModel->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $actionID = $this->objectModel->create($objectType, $objectID, $actionType, $comment, $extra, $actor);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $actionID ? $this->objectModel->getById($actionID) : 0;
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
        $this->objectModel->read($objectType, $objectID);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll();
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
        $objects = $this->objectModel->getUnreadActions($actionID);
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
        $objects = $this->objectModel->getRelatedFields($objectType, $objectID, $actionType, $extra);
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
        $objects = $this->objectModel->getList($objectType, $objectID);
        $modules = $objectType == 'module' ? $this->objectModel->dao->select('id')->from(TABLE_MODULE)->where('root')->in($objectID)->fetchPairs('id') : array();
        $actions = $this->objectModel->getActionListByTypeAndID($objectType, $objectID, $modules);
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

        $actions = $this->objectModel->dao->select('objectType, action')->from(TABLE_ACTION)->where('id')->in($actionIdList)->fetchAll();
        $result  = $this->objectModel->processProjectActions($actions);
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
        $object = $this->objectModel->getById((int)$actionID);
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
        $objects = $this->objectModel->getTrashesBySearch($objectType, $type, $queryID, $orderBy, $pager);
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
        $objects = $this->objectModel->getTrashes($objectType, $type, $orderBy, $pager);
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
        $objects = $this->objectModel->getTrashObjectTypes($type);
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
        $result = $this->objectModel->getHistory($actionID);
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
        $result = $this->objectModel->logHistory($actionID, $changes);
        if(dao::isError()) return dao::getError();

        $histories = $this->objectModel->getHistory($actionID);
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
        $objects = $this->objectModel->getActionCondition($module);
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
        if($queryID) $this->objectModel->session->set('actionQuery', null);

        if($date == 'today') $date = date('Y-m-d');
        $objects = $this->objectModel->getDynamicBySearch($queryID, $orderBy, $limit, $date, $direction);
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
        $objects = $this->objectModel->getDynamic($account, $period, 'date_desc', 50, $productID, $projectID, $executionID, $date, $direction);
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
        $objects = $this->objectModel->getDynamicByProduct((int)$productID, $account, $period, 'date_desc', 50, $date, $direction);
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
        $objects = $this->objectModel->getDynamicByProject((int)$projectID, $account, $period, $orderBy, $limit, $date, $direction);
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
        $objects = $this->objectModel->getDynamicByExecution((int)$executionID, $account, $period, 'date_desc', 50, $date, $direction);
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
        $objects = $this->objectModel->getDynamicByAccount($account, $period, 'date_desc', 50, $date, $direction);
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
        $objects = $this->objectModel->getBySQL($sql, $orderBy);
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
        $actions = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id', false);
        $objects = $this->objectModel->transformActions($actions);
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
        $actions = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');
        list($objectNames, $relatedProjects, $requirements) = $this->objectModel->getRelatedDataByActions($actions);
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
        $object = $this->objectModel->getObjectLabel($objectType, $objectID, $actionType, $requirements, $epics);
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
        $this->objectModel->app->methodName = 'dynamic';
        $result = $this->objectModel->computeBeginAndEnd($period, $date, $direction);

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
        $this->objectModel->deleteByType($objectType);
        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($objectType)->fetchAll();
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
        $result = $this->objectModel->undelete($actionID);
        if(dao::isError()) return dao::getError();
        if(is_string($result) || !$result) return $result;

        $action = $this->objectModel->getByID($actionID);
        $table  = $this->objectModel->config->objectTables[$action->objectType];
        $object = $this->objectModel->dao->select('deleted')->from($table)->where('id')->eq($action->objectID)->fetch();
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
        $this->objectModel->hideOne($actionID);
        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($actionID);
    }

    /**
     * Test hide all deleted objects.
     *
     * @access public
     * @return array
     */
    public function hideAllTest()
    {
        $this->objectModel->hideAll();
        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('id,extra')->from(TABLE_ACTION)->where('action')->eq('deleted')->fetchAll();
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
            $this->objectModel->session->set('album', null);
            $this->objectModel->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $action = new stdclass();
        $action->lastComment = $comment;
        $action->uid         = $uid;
        $action->deleteFiles = array();
        $action->renameFiles = array();

        $this->objectModel->updateComment($actionID, $action);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($actionID);
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
        $this->objectModel->session->set('actionQueryCondition', '1=1');
        if($date == 'today') $date = date('Y-m-d');
        $result = $this->objectModel->hasPreOrNext($date, $direction);
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
        $result = $this->objectModel->saveIndex($objectType, $objectID, $actionType);
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
        return $this->objectModel->processDynamicForAPI($dynamics);
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
        $objects = $this->objectModel->buildDateGroup($actions, $direction, $orderBy);
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
        $this->objectModel->restoreStages($stageList);
        if(dao::isError()) return dao::getError();

        $actionList = array();
        foreach($actionIdList as $actionID) $actionList[$actionID] = $this->objectModel->getByID($actionID);
        foreach($stageList as $stageId)
        {
            $stage = $this->objectModel->dao->select('id, deleted')->from(TABLE_STAGE)->where('id')->eq($stageId)->fetch();
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
        $action = $this->objectModel->getById($actionID);
        $result = $this->objectModel->getRepeatObject($action, $table);
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
        $this->objectModel->updateStageAttribute($attribute, $stages);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stages)->fetchAll();
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
        $objects = $this->objectModel->getDeletedStagedByList($list);
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
        $this->objectModel->updateObjectByID($table, $id, $updateParams);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from($table)->where('id')->eq($id)->fetch();
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
        $objects = $this->objectModel->getLikeObject($table, $field, $param, $value);
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
        $object = $this->objectModel->getFirstAction();
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
        $history = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('id')->eq($historyID)->fetch();
        return $this->objectModel->processHistory($history);
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
        $histories = $this->objectModel->dao->select('*')->from(TABLE_HISTORY)->where('id')->eq($historyID)->fetchAll('id', false);
        $content   = $this->objectModel->renderChanges($objectType, 0, $histories);
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
        return $this->objectModel->checkActionClickable($action, $deptUser, $moduleName, $methodName);
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
        $result = $this->objectModel->getAccountFirstAction($account);
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
        $result = $this->objectModel->renderAction($action, $desc);
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
        $result = $this->objectModel->formatActionComment($comment);
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
        $this->objectModel->printAction($action, $desc);
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
        $this->objectModel->printChanges($objectType, $objectID, $histories, $canChangeTag);
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
        $this->objectModel->printActionForGitLab($action);
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
        $result = $this->objectModel->processActionForAPI($actions, $users, $objectLang);
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
        $originalConfig = $this->objectModel->config->trash->search ?? null; // 备份原始配置

        $this->objectModel->buildTrashSearchForm($queryID, $actionURL);
        if(dao::isError()) return dao::getError();

        // 获取设置后的配置值
        $result = array(
            'actionURL' => $this->objectModel->config->trash->search['actionURL'] ?? '',
            'queryID'   => $this->objectModel->config->trash->search['queryID'] ?? 0
        );

        if($originalConfig !== null) $this->objectModel->config->trash->search = $originalConfig; // 恢复原始配置

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
        $result = $this->objectModel->getAttributeByExecutionID($executionID);
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
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getObjectTypeTeamParams');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $action);
        if(dao::isError()) return dao::getError();
        return $result;
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
        $result = $this->objectTao->processAttribute($type);
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
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('checkActionCanUndelete');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $action, $object);
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
        $result = $this->objectTao->getLinkedExtra($action, $type);
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
            $originalIsonlybody = $this->objectModel->config->requestType ?? 'GET';
            $_GET['onlybody'] = 'yes';
        }

        $this->objectTao->processActionExtra($table, $action, $fields, $type, $method, $onlyBody, $addLink);
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

        $result = $this->objectTao->processStoryGradeActionExtra($action);
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
        $result = $this->objectTao->processParamString($action, $type);
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

        $this->objectTao->processCreateChildrenActionExtra($action);
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

        $this->objectTao->processCreateRequirementsActionExtra($action);
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

        $this->objectTao->processAppendLinkByExtra($action);
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

        $this->objectTao->processLinkStoryAndBugActionExtra($action, $module, $method);
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
        $this->objectTao->processToStoryActionExtra($action);
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
        $result = $this->objectTao->getActionTable($period);
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

        $this->objectTao->addObjectNameForAction($action, $objectNames, $objectType);
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
        $this->objectTao->processMaxDocObjectLink($action, $moduleName, $methodName, $vars);
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
        $result = $this->objectTao->getDocLibLinkParameters($action);
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
        $result = $this->objectTao->getDoclibTypeParams($action);
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
        $result = $this->objectTao->getObjectLinkParams($action, $vars);
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
        $result = $this->objectTao->getReleaseRelated($objectType, $objectID);
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
        $result = $this->objectTao->getReviewRelated($objectType, $objectID);
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
        $result = $this->objectTao->getNeedRelatedFields($objectType, $objectID, $actionType, $extra);
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
        $result = $this->objectTao->getActionListByTypeAndID($objectType, $objectID, $modules);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
