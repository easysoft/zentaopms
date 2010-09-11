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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class actionModel extends model
{
    const CAN_UNDELETED = 1;    // 标记extra字段为可以还原。
    const BE_UNDELETED  = 0;    // 标记extra字段为已经还原。

    /* 创建一条action动作。*/
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

    /* 返回某一个对象的所有action列表。*/
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

    /* 获得action信息。*/
    public function getById($actionID)
    {
        return $this->dao->findById((int)$actionID)->from(TABLE_ACTION)->fetch();
    }

    /* 获得所有的删除记录列表。*/
    public function getTrashes($orderBy, $pager)
    {
        $trashes = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq(self::CAN_UNDELETED)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        if(!$trashes) return array();
        
        /* 将对象按照类型分开，然后查找其对应的名称。*/
        foreach($trashes as $object) $typeTrashes[$object->objectType][] = $object->objectID;
        foreach($typeTrashes as $objectType => $objectIds)
        {
            $objectIds   = array_unique($objectIds);
            $table       = $this->config->action->objectTables[$objectType];
            $field       = $this->config->action->objectNameFields[$objectType];
            $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();
        }

        /* 将name字段添加到trashes中。*/
        foreach($trashes as $trash) $trash->objectName = $objectNames[$trash->objectType][$trash->objectID];
        return $trashes;
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

    /* 打印action标题，显示在每一个对象的详情界面。*/
    public function printAction($action)
    {
        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         * 判断使用哪一种描述。如果该模块有对应的描述，则取之，然后则取action模块中对应的方法的描述。
         * 如果还没有，则判断当前action是否有extra信息，如果有，则取action模块的extra描述，最后使用通用的描述。
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

        /* 循环替换desc中对应的标签。*/
        foreach($action as $key => $value)
        {
            if($key == 'history') continue;

            /* desc可能是数组，也有可能是一个字符串。*/
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

        /* 如果desc是数组，再处理extra变量。例子参考bug模块的语言设置。*/
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
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->beginIF($objectType != 'all')->where('objectType')->eq($objectType)->fi()
            ->orderBy('id desc')->limit($count)->fetchAll();
        if(!$actions) return array();
        foreach($actions as $action)
        {
            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);
            $action->date        = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->action;
            $action->objectLabel = isset($this->lang->action->label->$objectType) ? $this->lang->action->label->$objectType : $objectType;

            /* 处理login和logout动作。*/
            if($actionType == 'login' or $actionType == 'logout')
            {
                $action->objectLink  = '';
                $action->objectLabel = '';
                continue;
            }

            /* 其他的动作生成相应的链接。*/
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
        if(empty($histories)) return;

        /* 计算字段的最大长度，并将历史记录根据是否有diff分开，以保证含有diff的字段显示在最后面。*/
        $maxLength            = 0;
        $historiesWithDiff    = array();
        $historiesWithoutDiff = array();

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
                printf($this->lang->action->desc->diff2, $history->fieldLabel, nl2br($history->diff));
            }
            else
            {
                printf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
    }
}
