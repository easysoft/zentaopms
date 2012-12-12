<?php
/**
 * The model file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class actionModel extends model
{
    const CAN_UNDELETED = 1;    // The deleted object can be undeleted or not.
    const BE_UNDELETED  = 0;    // The deleted object has been undeleted or not.
    const BE_HIDDEN     = 2;

    /**
     * Create a action.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType 
     * @param  string $comment 
     * @param  string $extra        the extra info of this action, according to different modules and actions, can set different extra.
     * @access public
     * @return int
     */
    public function create($objectType, $objectID, $actionType, $comment = '', $extra = '')
    {
        $action = new stdclass();

        $objectType = str_replace('`', '', $objectType);
        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->actor      = $this->app->user->account;
        $action->action     = strtolower($actionType);
        $action->date       = helper::now();
        $action->comment    = htmlspecialchars($comment);
        $action->extra      = $extra;

        /* Get product and project for this object. */
        $productAndProject  = $this->getProductAndProject($objectType, $objectID);
        $action->product    = $productAndProject['product'];
        $action->project    = $productAndProject['project'];

        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        return $this->dbh->lastInsertID();
    }

    /**
     * Update read field of action when view a task/bug.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @access public
     * @return void
     */
    public function read($objectType, $objectID)
    {
        $this->dao->update(TABLE_ACTION)
            ->set('`read`')->eq(1)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->andWhere('`read`')->eq(0)
            ->exec();
    }

    /**
     * Get the unread actions.
     * 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function getUnreadActions($actionID = 0)
    {
        if(!is_numeric($actionID)) $actionID = 0;
        $objectList['task'] = TABLE_TASK;
        $objectList['bug']  = TABLE_BUG;
        $actions = array();

        foreach($objectList as $object => $table)
        {
            $idList = $this->dao->select('id')->from($table)->where('assignedTo')->eq($this->app->user->account)->fetchPairs('id', '', false);

            $tmpActions = $this->dao->select('*')->from(TABLE_ACTION)
                ->where('objectType')->eq($object)
                ->andWhere('objectID')->in($idList)
                ->andWhere('`read`')->eq(0)
                ->andWhere('id')->gt($actionID)
                ->fetchAll('id');

            if(empty($tmpActions)) continue;

            $tmpActions = $this->transformActions($tmpActions);
            foreach($tmpActions as $action)
            {
                $actions[$action->objectType][] = array(
                    'actionID'   => $action->id,
                    'objectType' => $action->objectType,
                    'objectID'   => $action->objectID,
                    'action'     => $action->actor . ' ' . $action->actionLabel . ' ' . $action->objectType . " #$action->objectID" . $action->objectName
                );
            }
        }
        return json_encode($actions);
    }

    /**
     * Get product and project of an object.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @access public
     * @return array
     */
    public function getProductAndProject($objectType, $objectID)
    {
        $objectType  = strtolower($objectType);
        $emptyRecord = array('product' => ',0,', 'project' => 0);

        /* If objectType is product or project, return the objectID. */
        if($objectType == 'product') return array('product' => ",$objectID,", 'project' => 0);
        if($objectType == 'project') 
        {
            $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetchPairs('product');
            $productList = ',' . join(',', array_keys($products)) . ',';
            return array('project' => $objectID, 'product' => $productList);
        }

        /* Only process these object types. */
        if(strpos('story, productplan, release, task, build. bug, case, testtask, doc', $objectType) !== false)
        {
            if(!isset($this->config->action->objectTables[$objectType])) return $emptyRecord;

            /* Set fields to fetch. */
            if(strpos('story, productplan, case',  $objectType) !== false) $fields = 'product';
            if(strpos('build, bug, testtask, doc', $objectType) !== false) $fields = 'product, project';
            if($objectType == 'release') $fields = 'product, build';
            if($objectType == 'task')    $fields = 'project, story';

            $record = $this->dao->select($fields)->from($this->config->action->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

            /* Process story, release and task. */
            if($objectType == 'story')   $record->project = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($objectID)->fetch('project');
            if($objectType == 'release') $record->project = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->eq($record->build)->fetch('project');
            if($objectType == 'task')    
            {
                if($record->story != 0)
                {
                    $product = $this->dao->select('product')->from(TABLE_STORY)->where('id')->eq($record->story)->fetchPairs('product');
                    $record->product = join(',', array_keys($product));
                }
                else
                {
                    $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($record->project)->fetchPairs('product');
                    $record->product = join(',', array_keys($products));
                }
            }

            if($record)
            {
                $record = (array)$record;
                $record['product'] = isset($record['product']) ? ',' . $record['product'] . ',' : ',0,';
                if(!isset($record['project'])) $record['project'] = 0;
                return $record;
            }

            return $emptyRecord;
        }
        return $emptyRecord;
    }

    /**
     * Get actions of an object.
     * 
     * @param  int    $objectType 
     * @param  int    $objectID 
     * @access public
     * @return array
     */
    public function getList($objectType, $objectID)
    {
        $commiters = $this->loadModel('user')->getCommiters();
        $actions   = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('date, id')->fetchAll('id');
        $histories = $this->getHistory(array_keys($actions));
        foreach($actions as $actionID => $action)
        {
            if(strtolower($action->action) == 'svncommited' and isset($commiters[$action->actor])) $action->actor = $commiters[$action->actor];
            $action->history = isset($histories[$actionID]) ? $histories[$actionID] : array();
            $actions[$actionID] = $action;
        }
        return $actions;
    }

    /**
     * Get an action record.
     * 
     * @param  int    $actionID 
     * @access public
     * @return object
     */
    public function getById($actionID)
    {
        return $this->dao->findById((int)$actionID)->from(TABLE_ACTION)->fetch();
    }

    /**
     * Get deleted objects.
     * 
     * @param  string    $orderBy 
     * @param  object    $pager 
     * @access public
     * @return array
     */
    public function getTrashes($orderBy, $pager)
    {
        $trashes = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq(self::CAN_UNDELETED)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        if(!$trashes) return array();
        
        /* Group trashes by objectType, and get there name field. */
        foreach($trashes as $object) 
        {
            $object->objectType = str_replace('`', '', $object->objectType);
            $typeTrashes[$object->objectType][] = $object->objectID;
        }
        foreach($typeTrashes as $objectType => $objectIds)
        {
            $objectIds   = array_unique($objectIds);
            $table       = $this->config->action->objectTables[$objectType];
            $field       = $this->config->action->objectNameFields[$objectType];
            $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();
        }

        /* Add name field to the trashes. */
        foreach($trashes as $trash) $trash->objectName = $objectNames[$trash->objectType][$trash->objectID];
        return $trashes;
    }

    /**
     * Get histories of an action.
     * 
     * @param  int    $actionID 
     * @access public
     * @return array
     */
    public function getHistory($actionID)
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->orderBy('id')->fetchGroup('action');
    }

    /**
     * Log histories for an action.
     * 
     * @param  int    $actionID 
     * @param  array  $changes 
     * @access public
     * @return void
     */
    public function logHistory($actionID, $changes)
    {
        foreach($changes as $change) 
        {
            $change['action'] = $actionID;
            $this->dao->insert(TABLE_HISTORY)->data($change)->exec();
        }
    }

    /**
     * Print actions of an object.
     * 
     * @param  array    $action 
     * @access public
     * @return void
     */
    public function printAction($action)
    {
        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         * Set the desc string of this action.
         *
         * 1. If the module of this action has defined desc of this actionType, use it.
         * 2. If no defined in the module language, search the common action define.
         * 3. If not found in the lang->action->desc, use the $lang->action->desc->common or $lang->action->desc->extra as the default.
         */
        if(isset($this->lang->$objectType->action->$actionType))
        {
            $desc = $this->lang->$objectType->action->$actionType;
        }
        elseif(isset($this->lang->action->desc->$actionType))
        {
            $desc = $this->lang->action->desc->$actionType;
        }
        else
        {
            $desc = $action->extra ? $this->lang->action->desc->extra : $this->lang->action->desc->common;
        }

        /* Cycle actions, replace vars. */
        foreach($action as $key => $value)
        {
            if($key == 'history') continue;

            /* Desc can be an array or string. */
            if(is_array($desc))
            {
                if($key == 'extra') continue;
                $desc['main'] = str_replace('$' . $key, $value, $desc['main']);
            }
            else
            {
                $desc = str_replace('$' . $key, $value, $desc);
            }
        }

        /* If the desc is an array, process extra. Please bug/lang. */
        if(is_array($desc))
        {
            $extra = strtolower($action->extra);
            if(isset($desc['extra'][$extra])) 
            {
                echo str_replace('$extra', $desc['extra'][$extra], $desc['main']);
            }
            else
            {
                echo str_replace('$extra', $action->extra, $desc['main']);
            }
        }
        else
        {
            echo $desc;
        }
    }

    /**
     * Get actions as dynamic.
     * 
     * @param  string $objectType 
     * @param  string $count 
     * @param  string $period 
     * @param  string $orderBy 
     * @param  object $pager
     * @param  string|int $productID   all|int(like 123)|notzero   all => include zeror, notzero, great than 0
     * @param  string|int $projectID   same as productID
     * @access public
     * @return array
     */
    public function getDynamic($account = 'all', $period = 'all', $orderBy = 'date_desc', $pager = null, $productID = 'all', $projectID = 'all')
    {
        /* Computer the begin and end date of a period. */
        $beginAndEnd = $this->computeBeginAndEnd($period);
        extract($beginAndEnd);

        /* Get actions. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where(1)
            ->beginIF($period != 'all')->andWhere('date')->gt($begin)->fi()
            ->beginIF($period != 'all')->andWhere('date')->lt($end)->fi()
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF(is_numeric($productID))->andWhere('product')->like("%,$productID,%")->fi()
            ->beginIF(is_numeric($projectID))->andWhere('project')->eq($projectID)->fi()
            ->beginIF($productID == 'notzero')->andWhere('product')->gt(0)->fi()
            ->beginIF($projectID == 'notzero')->andWhere('project')->gt(0)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();

        if(!$actions) return array();
        return $this->transformActions($actions);
    }

    /**
     * Get dynamic by search. 
     * 
     * @param  array  $products 
     * @param  array  $projects 
     * @param  int    $queryID 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array 
     */
    public function getDynamicBySearch($products, $projects, $queryID, $orderBy = 'date_desc', $pager)
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
        if($query)
        {
            $this->session->set('actionQuery', $query->sql);
            $this->session->set('actionForm', $query->form);
        }
        if($this->session->actionQuery == false) $this->session->set('actionQuery', ' 1 = 1');

        $allProduct          = "`product` = 'all'";
        $allProject          = "`project` = 'all'";
        $actionQuery = $this->session->actionQuery;

        $productID = 0;
        if(preg_match("/`product` = '(\d*)'/", $actionQuery, $out))
        {
            $productID = $out[1];
        }
        /* If the sql not include 'product', add check purview for product. */
        if(strpos($actionQuery, $allProduct) === false)
        {
            if(!in_array($productID, array_keys($products))) return array();
        }
        else
        {
            $actionQuery = str_replace($allProduct, '1', $actionQuery);
        }

        /* If the sql not include 'project', add check purview for project. */
        if(strpos($actionQuery, $allProject) === false)
        {
            $actionQuery = $actionQuery . 'AND `project`' . helper::dbIN(array_keys($projects));
        }
        else
        {
            $actionQuery = str_replace($allProject, '1', $actionQuery);
        }

        $actionQuery = str_replace("`product` = '$productID'", "`product` LIKE '%,$productID,%'", $actionQuery);

        $actions = $this->getBySQL($actionQuery, $orderBy, $pager);
        if(!$actions) return array();
        return $this->transformActions($actions);
    }

    /**
     * Get actions by SQL. 
     * 
     * @param  string $sql 
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array 
     */
    public function getBySQL($sql, $orderBy, $pager = null)
    {
         return $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where($sql)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Transform the actions for display.
     * 
     * @param  int    $actions 
     * @access public
     * @return void
     */
    public function transformActions($actions)
    {
        /* Get commiters. */
        $commiters = $this->loadModel('user')->getCommiters();

        /* Group actions by objectType, and get there name field. */
        foreach($actions as $object) $objectTypes[$object->objectType][] = $object->objectID;
        foreach($objectTypes as $objectType => $objectIds)
        {
            if(!isset($this->config->action->objectTables[$objectType])) continue;    // If no defination for this type, omit it.

            $objectIds   = array_unique($objectIds);
            $table       = $this->config->action->objectTables[$objectType];
            $field       = $this->config->action->objectNameFields[$objectType];
            if($table != '`zt_todo`')
            {
                $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();
            }
            else
            {
                $todos = $this->dao->select("id, $field AS name, account, private")->from($table)->where('id')->in($objectIds)->fetchAll('id');
                foreach($todos as $id => $todo)
                {
                    if($todo->private == 1 and $todo->account != $this->app->user->account) 
                    {
                       $objectNames[$objectType][$id] = $this->lang->todo->thisIsPrivate;
                    }
                    else
                    {
                       $objectNames[$objectType][$id] = $todo->name;
                    }
                }
            } 
        }
        $objectNames['user'][0] = 'guest';    // Add guest account.

        foreach($actions as $action)
        {
            /* Add name field to the actions. */
            $action->objectName = isset($objectNames[$action->objectType][$action->objectID]) ? $objectNames[$action->objectType][$action->objectID] : '';

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);
            $action->date        = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->action;
            $action->objectLabel = isset($this->lang->action->label->$objectType) ? $this->lang->action->label->$objectType : $objectType;

            /* If action type is login or logout, needn't link. */
            if($actionType == 'login' or $actionType == 'logout')
            {
                $action->objectLink  = '';
                $action->objectLabel = '';
                continue;
            }
            elseif($actionType == 'svncommited')
            {
                $action->actor = isset($commiters[$action->actor]) ? $commiters[$action->actor] : $action->actor;
            }

            /* Other actions, create a link. */
            if(strpos($action->objectLabel, '|') !== false)
            {
                list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
                $action->objectLink  = helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID));
                $action->objectLabel = $objectLabel;
            }
            else
            {
                $action->objectLink = '';
            }
        }
        return $actions;
    }

    /**
     * Compute the begin date and end date of a period.
     * 
     * @param  string    $period   all|today|yesterday|twodaysago|latest2days|thisweek|lastweek|thismonth|lastmonth
     * @access public
     * @return array
     */
    public function computeBeginAndEnd($period)
    {
        $this->loadModel('todo');

        $today      = $this->todo->today();
        $tomorrow   = $this->todo->tomorrow();
        $yesterday  = $this->todo->yesterday();
        $twoDaysAgo = $this->todo->twoDaysAgo();

        $period = strtolower($period);

        if($period == 'all')        return array('begin' => '1970-1-1',  'end' => '2109-1-1');
        if($period == 'today')      return array('begin' => $today,      'end' => $tomorrow);
        if($period == 'yesterday')  return array('begin' => $yesterday,  'end' => $today);
        if($period == 'twodaysago') return array('begin' => $twoDaysAgo, 'end' => $yesterday);
        if($period == 'latest3days')return array('begin' => $twoDaysAgo, 'end' => $tomorrow);

        /* If the period is by week, add the end time to the end date. */
        if($period == 'thisweek' or $period == 'lastweek')
        {
            $func = "get$period";
            extract($this->todo->$func());
            return array('begin' => $begin, 'end' => $end . ' 23:59:59');
        }

        if($period == 'thismonth')  return $this->todo->getThisMonth();
        if($period == 'lastmonth')  return $this->todo->getLastMonth();
    }

    /**
     * Print changes of every action.
     * 
     * @param  string    $objectType 
     * @param  array     $histories 
     * @access public
     * @return void
     */
    public function printChanges($objectType, $histories)
    {
        if(empty($histories)) return;

        $maxLength            = 0;          // The max length of fields names.
        $historiesWithDiff    = array();    // To save histories without diff info.
        $historiesWithoutDiff = array();    // To save histories with diff info.

        /* Diff histories by hasing diff info or not. Thus we can to make sure the field with diff show at last. */
        foreach($histories as $history)
        {
            $fieldName = $history->field;
            $history->fieldLabel = isset($this->lang->$objectType->$fieldName) ? $this->lang->$objectType->$fieldName : $fieldName;
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
            $history->diff ? $historiesWithDiff[] = $history : $historiesWithoutDiff[] = $history;
        }
        $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                $history->diff = str_replace(array('<ins>', '</ins>', '<del>', '</del>'), array('[ins]', '[/ins]', '[del]', '[/del]'), $history->diff);
                $history->diff = $history->field != 'subversion' ? htmlspecialchars($history->diff) : $history->diff;   // Keep the diff link.
                $history->diff = str_replace(array('[ins]', '[/ins]', '[del]', '[/del]'), array('<ins>', '</ins>', '<del>', '</del>'), $history->diff);
                $history->diff = nl2br($history->diff);
                printf($this->lang->action->desc->diff2, $history->fieldLabel, $history->diff);
            }
            else
            {
                printf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
    }

    /**
     * Hide object. 
     * 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function hide($actionID)
    {
        $action = $this->getById($actionID);
        if($action->action != 'deleted') return;
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(self::BE_HIDDEN)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'hidden');
    }
}
