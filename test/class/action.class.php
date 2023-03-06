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
    public function createTest($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $autoDelete = true)
    {
        $_SERVER['HTTP_HOST'] = 'pms.zentao.com';
        $_POST['uid'] = '';
        $objectID = $this->objectModel->create($objectType, $objectID, $actionType, $comment, $extra, $actor, $autoDelete);

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
     * Test get product, project, execution of the object.
     *
     * @param String $objectType
     * @param Int    $objectID
     * @param String $actionType
     * @param String $extra
     * @access public
     * @return array
     */
    public function getRelatedFieldsTest($objectType, $objectID, $actionType = '', $extra = '')
    {
        $objects = $this->objectModel->getRelatedFields($objectType, $objectID, $actionType = '', $extra = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get actions of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return object
     */
    public function getListTest($objectType, $objectID)
    {
        $objects = $this->objectModel->getList($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        $dirname = dirname(__DIR__) . DS;

        $objects[$objectID]->extra = str_replace($dirname, '', $objects[$objectID]->extra);
        $objects[$objectID]->extra = trim($objects[$objectID]->extra, "\n");
        if(strpos($objects[$objectID]->extra, 'href') !== false) $objects[$objectID]->extra = 'a';

        return $objects[$objectID];
    }

    /**
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
     * getTrashesBySearchTest
     *
     * @param  string $objectType
     * @param  string $type all|hidden
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getTrashesBySearchTest($objectType, $type, $queryID, $orderBy, $pager = null)
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

        return $objects[$actionID];
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
     * Get dynamic by search.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamicBySearchTest($products, $projects, $executions, $queryID, $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next')
    {
        $objects = $this->objectModel->getDynamicBySearch($products, $projects, $executions, $queryID, $orderBy, $pager, $date, $direction);

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
        $objects = $this->objectModel->getDynamic($account, $period, 'date_desc', null, $productID, $projectID, $executionID, $date, $direction);

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
    public function getBySQLTest($sql, $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getBySQL($sql, $orderBy, $pager = null);

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

        $objects = $this->objectModel->getRelatedDataByActions($actions);

        if(dao::isError()) return dao::getError();

        return $objects[$field];
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
        $tomorrow   = date::tomorrow();
        $yesterday  = date::yesterday();
        $twoDaysAgo = date::twoDaysAgo();

        if($period == 'all')         return $date['begin'] == '1970-1-1' and $date['end'] == '2109-1-1';
        if($period == 'today')       return $date['begin'] == $today and $date['end'] == $tomorrow;
        if($period == 'yesterday')   return $date['begin'] == $yesterday and $date['end'] == $today;
        if($period == 'twodaysago')  return $date['begin'] == $twoDaysAgo and $date['end'] == $yesterday;
        if($period == 'latest3days') return $date['begin'] == $twoDaysAgo and $date['end'] == $tomorrow;
        if($period == 'thismonth')   return $date == date::getThisMonth();
        if($period == 'lastmonth')   return $date == date::getLastMonth();
        $func = "get$period";
        extract(date::$func());
        if($period == 'thisweek')    return $date['begin'] == $begin and $date['end'] == $end . ' 23:59:59';
        if($period == 'lastweek')    return $date['begin'] == $begin and $date['end'] == $end . ' 23:59:59';
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
     * Test undelete a record.
     *
     * @param  int    $actionID
     * @access public
     * @return object
     */
    public function undeleteTest($actionID)
    {
        $this->objectModel->undelete($actionID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($actionID);
        return $object;
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
     * @param  string $comment
     * @access public
     * @return object
     */
    public function updateCommentTest($actionID, $comment)
    {
        $_POST['lastComment'] = $comment;

        $this->objectModel->updateComment($actionID);

        unset($_POST);
        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByID($actionID);
        return $object;
    }

    /**
     * Check Has pre or next.
     *
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return bool
     */
    public function hasPreOrNextTest($date, $direction = 'next')
    {
        global $tester;
        $tester->session->set('actionQueryCondition', '1=1');
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
    public function saveIndexTest($objectType, $objectID, $actionType)
    {
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
     * Test build date group by actions.
     *
     * @param  string $direction
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return int
     */
    public function buildDateGroupTest($direction = 'next', $type = 'today', $orderBy = 'date_desc')
    {
        $actions = $this->objectModel->getDynamic('all', $type);
        $objects = $this->objectModel->buildDateGroup($actions, $direction, $type, $orderBy);

        if(dao::isError()) return dao::getError();

        $count = 0;
        foreach($objects as $object) $count += count($object);
        return $count;
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

        return $actionList;
    }
}
