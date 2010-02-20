<?php
/**
 * The model file of action module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class actionModel extends model
{
    /* 创建一条action动作。*/
    public function create($objectType, $objectID, $actionType, $comment = '', $extra = '')
    {
        $action->company    = $this->app->company->id;
        $action->objectType = $objectType;
        $action->objectID   = $objectID;
        $action->actor      = $this->app->user->account;
        $action->action     = $actionType;
        $action->date       = time();
        $action->comment    = htmlspecialchars($comment);
        $action->extra      = $extra;
        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        return $this->dbh->lastInsertID();
    }

    /* 返回某一个对象的所有action列表。*/
    public function getList($objectType, $objectID)
    {
        $actions = array();
        $stmt = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq($objectType)->andWhere('objectID')->eq($objectID)->orderBy('id')->query();
        while($action = $stmt->fetch())
        {
            $action->date = date('Y-m-d H:i:s', $action->date);
            $actions[$action->id] = $action;
        }

        $histories = $this->getHistory(array_keys($actions));
        foreach($actions as $actionID => $action)
        {
            $action->history = isset($histories[$actionID]) ? $histories[$actionID] : array();
            $actions[$actionID] = $action;
        }
        return $actions;
    }

    /* 获得action信息。*/
    public function getById($actionID)
    {
        $action = $this->dao->findById((int)$actionID)->from(TABLE_ACTION)->fetch();
        $action->date = date('Y-m-d H:i:s', $action->date);
        return $action;
    }

    /* 返回某一个action所对应的字段修改记录。*/
    public function getHistory($actionID)
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->orderBy('id')->fetchGroup('action');
    }

    /* 记录历史。*/
    public function logHistory($actionID, $changes)
    {
        foreach($changes as $change) 
        {
            $change['action'] = $actionID;
            $this->dao->insert(TABLE_HISTORY)->data($change)->exec();
        }
    }

    /* 打印action标题。*/
    public function printAction($action)
    {
        $objectType = $action->objectType;
        $actionType = strtolower($action->action);
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

        foreach($action as $key => $value)
        {
            if($key == 'history') continue;
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

    /* 打印动态信息。*/
    public function getDynamic($objectType = 'all', $count = 30)
    {
        $actions = $this->dao->select('*')->from(TABLE_ACTION)->onCaseOf($objectType != 'all')->where('objectType')->eq($objectType)->endCase()->orderBy('id desc')->limit($count)->fetchAll();
        if(!$actions) return array();
        foreach($actions as $action)
        {
            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);
            $action->date        = date('H:i', $action->date);
            $action->actionLabel = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->action;
            $action->objectLabel = isset($this->lang->action->label->$objectType) ? $this->lang->action->label->$objectType : $objectType;
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

    /* 打印修改记录。*/
    public function printChanges($objectType, $histories)
    {
        $maxLength = 0;
        foreach($histories as $history)
        {
            $fieldName  = $history->field;
            $history->fieldLabel = isset($this->lang->$objectType->$fieldName) ? $this->lang->$objectType->$fieldName : $fieldName;
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
        }
        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                printf($this->lang->action->desc->diff2, $history->fieldLabel, nl2br($history->diff));
            }
            else
            {
                printf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
    }
}
