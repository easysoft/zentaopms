<?php
class actionTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('action');
         $tester->dao->delete()->from(TABLE_ACTION)->where('action')->eq('login')->exec();
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
        global $tester, $config;
        if($tester->app->upgrading && !empty($version))
        {
            global $tester;
            $data = new stdclass();
            $data->value   = $version;
            $data->key     = 'version';
            $data->owner   = 'system';
            $data->module  = 'common';
            $data->section = 'global';
            $tester->dao->replace(TABLE_CONFIG)->data($data)->exec();
        }

        $_SERVER['HTTP_HOST'] = 'pms.zentao.com';
        if($uid)
        {
            $_POST['uid'] = $uid;
            global $tester;
            $tester->session->set('album', null);
            $tester->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $objectID = $this->objectModel->create($objectType, $objectID, $actionType, $comment, $extra, $actor);

        unset($_POST);
        if(dao::isError()) return dao::getError();

        return $objectID ? $this->objectModel->getById($objectID) : 0;
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

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll();
        return $objects;
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
    public function getListTest(string $objectType, int $objectID, string $edition = 'pms'): string
    {
        global $tester;
        $oldEdition = $tester->config->edition;
        $tester->config->edition = $edition;

        $objects  = $this->objectModel->getList($objectType, $objectID);

        $tester->config->edition = $oldEdition;

        global $tester;
        $modules   = $objectType == 'module' ? $tester->dao->select('id')->from(TABLE_MODULE)->where('root')->in($objectID)->fetchPairs('id') : array();
        $actions   = $this->objectModel->getActionListByTypeAndID($objectType, $objectID, $modules);

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
     * @param  array  $actions
     * @access public
     * @return string
     */
    public function processProjectActionsTest($actions)
    {
        global $tester;
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

        $objects = $this->objectModel->processProjectActions($actions);

        if(dao::isError()) return dao::getError();

        $IDs = array_keys($objects);
        return implode(',', $IDs);
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
        $object = $this->objectModel->getById($actionID);

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
    public function getTrashesBySearchTest(string $objectType, string $type, string|int $queryID, string $orderBy, object $pager = null): array
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
     * @param  int    $actionID
     * @access public
     * @return array
     */
    public function getHistoryTest($actionID)
    {
        $objects = $this->objectModel->getHistory($actionID);

        if(dao::isError()) return dao::getError();

        return isset($objects[$actionID])? $objects[$actionID] : false;
    }

    /**
     * Test log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return array
     */
    public function logHistoryTest($actionID, $changes)
    {
        $this->objectModel->logHistory($actionID, $changes);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($actionID)->fetchAll();
        return $objects;
    }

    /**
     * Get dynamic show action.
     *
     * @access public
     * @return string
     */
    public function getActionConditionTest()
    {
        $objects = $this->objectModel->getActionCondition();

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
        if($queryID)
        {
            global $tester;
            $tester->session->set('actionQuery', null);
        }

        $date = $date == 'today' ? date('Y-m-d', time()) : $date;
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
        $date = $date == 'today' ? date('Y-m-d', time()) : $date;
        $objects = $this->objectModel->getDynamic($account, $period, 'date_desc', 50, $productID, $projectID, $executionID, $date, $direction);

        if(dao::isError()) return dao::getError();

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

        if(dao::isError()) return dao::getError();

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
        global $tester;
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

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
        global $tester;
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actions)->fetchAll('id');

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
     * @param  array $requirements
     * @access public
     * @return string
     */
    public function getObjectLabelTest($objectType, $objectID, $actionType, $requirements)
    {
        $object = $this->objectModel->getObjectLabel($objectType, $objectID, $actionType, $requirements);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test compute the begin date and end date of a period.
     *
     * @param  string $period
     * @access public
     * @return array
     */
    public function computeBeginAndEndTest($period)
    {
        $date = $this->objectModel->computeBeginAndEnd($period);

        $today      = date('Y-m-d');
        $tomorrow   = date('Y-m-d', strtotime('+1 days'));
        $yesterday  = date('Y-m-d', strtotime('-1 days'));
        $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));

        if($period == 'all')         return $date['begin'] == '1970-01-01' and $date['end'] == '2030-01-01';
        if($period == 'today')       return $date['begin'] == $today and $date['end'] == $tomorrow;
        if($period == 'yesterday')   return $date['begin'] == $yesterday and $date['end'] == $today;
        if($period == 'twodaysago')  return $date['begin'] == $twoDaysAgo and $date['end'] == $yesterday;
        if($period == 'latest3days') return $date['begin'] == $twoDaysAgo and $date['end'] == $tomorrow;
        if($period == 'thismonth')   return $date == date::getThisMonth();
        if($period == 'lastmonth')   return $date == date::getLastMonth();
        $func = "get$period";
        extract(date::$func());
        if($period == 'thisweek')    return $date['begin'] == $begin and $date['end'] == $end;
        if($period == 'lastweek')    return $date['begin'] == $begin and $date['end'] == $end;
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

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($objectType)->fetchAll();

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

        $object = $this->objectModel->getByID($actionID);

        global $tester;
        $table = $tester->config->objectTables[$object->objectType];

        $recoverObject = $tester->dao->select('*')->from($table)->where('id')->eq($object->objectID)->fetch();

        return isset($recoverObject->deleted) ? !$recoverObject->deleted : false;
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

        $object = $this->objectModel->getByID($actionID);
        return $object;
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

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_ACTION)->where('action')->eq('deleted')->fetchAll();
        return $objects;
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
            global $tester;
            $tester->session->set('album', null);
            $tester->session->set('album', array($uid => array(1), 'used' => array($uid => array(1))));
        }

        $this->objectModel->updateComment($actionID, $comment, $uid);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($actionID);
        return $object;
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
        global $tester;
        $tester->session->set('actionQueryCondition', '1=1');
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
        $objects = $this->objectModel->processDynamicForAPI($dynamics);

        if(dao::isError()) return dao::getError();

        return empty($objects) ? $objects : $objects[0];
    }

    /**
     * 测试搭建动态的日期分组。
     * Test build date group by actions.
     *
     * @param  string $direction
     * @param  string $type
     * @param  string $orderBy
     * @param  string $rawModule
     * @access public
     * @return array
     */
    public function buildDateGroupTest(string $direction = 'next', string $type = 'today', string $orderBy = 'date_desc', string $rawModule = 'my'): array
    {
        $actions = $this->objectModel->getDynamic('all', $type);

        global $tester;
        $tester->app->rawModule = $rawModule;
        $objects = $this->objectModel->buildDateGroup($actions, $direction, $orderBy);

        if(dao::isError()) return dao::getError();

        if(!$objects) $objects = array(array());
        return array('dateCount' => count($objects), 'dateActions' => count($objects, true) - count($objects));
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
        global $tester;
        foreach($stageList as $stageId)
        {
            $stage = $tester->dao->select('id, deleted')->from(TABLE_STAGE)->where('id')->eq($stageId)->fetch();
            if(!$stage || $stage->deleted) return false;
        }

        if(isset($actionList[2]) && $actionList[2]->extra == '0') return true;

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

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stages)->fetchAll();
        return $objects;
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

        global $tester;
        $object = $tester->dao->select('*')->from($table)->where('id')->eq($id)->fetch();
        return $object;
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

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试获取第一条动态。
     * Test get first action.
     *
     * @access public
     * @return array|object
     */
    public function getFirstActionTest(): array|object
    {
        $object = $this->objectModel->getFirstAction();

        if(dao::isError()) return dao::getError();

        return $object;
    }
}
