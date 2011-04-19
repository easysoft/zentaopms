<?php
/**
 * The model file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->actor      = $this->app->user->account;
        $action->action     = strtolower($actionType);
        $action->date       = helper::now();
        $action->comment    = htmlspecialchars($comment);
        $action->extra      = $extra;
        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        return $this->dbh->lastInsertID();
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
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id')->fetchAll('id');
        $histories = $this->getHistory(array_keys($actions));
        foreach($actions as $actionID => $action)
        {
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
        foreach($trashes as $object) $typeTrashes[$object->objectType][] = $object->objectID;
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
     * @param  int    $count 
     * @access public
     * @return array
     */
    public function getDynamic($account = 'all', $period = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $period = $this->computeBeginAndEnd($period);
        extract($period);

        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('date')->gt($begin)
            ->andWhere('date')->lt($end)
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->orderBy($orderBy)->page($pager)->fetchAll();

        if(!$actions) return array();

        /* Group trashes by objectType, and get there name field. */
        foreach($actions as $object) $objectTypes[$object->objectType][] = $object->objectID;
        foreach($objectTypes as $objectType => $objectIds)
        {
            $objectIds   = array_unique($objectIds);
            $table       = $this->config->action->objectTables[$objectType];
            $field       = $this->config->action->objectNameFields[$objectType];
            $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();
        }

        foreach($actions as $action)
        {
            /* Add name field to the trashes. */
            $action->objectName = $objectNames[$action->objectType][$action->objectID];

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

            /* Other actions, create a link. */
            if(strpos($action->objectLabel, '|') !== false)
            {
                list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
                $action->objectLink  = html::a(helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID)), '#' . $action->objectID);
                $action->objectLabel = $objectLabel;
            }
            else
            {
                $action->objectLink = '#' . $action->objectID;
            }
        }
        return $actions;
    }

    /**
     * Compute the begin date and end date of a period.
     * 
     * @param  string    $period 
     * @access private
     * @return array
     */
    private function computeBeginAndEnd($period)
    {
        $this->loadModel('todo');

        $today      = $this->todo->today();
        $tomorrow   = $this->todo->tomorrow();
        $yesterday  = $this->todo->yesterday();
        $twoDaysAgo = $this->todo->twoDaysAgo();

        if($period == 'all')        return array('begin' => '1970-1-1',  'end' => '2109-1-1');
        if($period == 'today')      return array('begin' => $today,      'end' => $tomorrow);
        if($period == 'yesterday')  return array('begin' => $yesterday,  'end' => $today);
        if($period == 'twodaysago') return array('begin' => $twoDaysAgo, 'end' => $yesterday);

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
                $history->diff = htmlspecialchars($history->diff);
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
}
