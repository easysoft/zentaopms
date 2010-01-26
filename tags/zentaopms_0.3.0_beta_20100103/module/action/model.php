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
 * @copyright   Copyright: 2009 Chunsheng Wang
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
    public function create($objectType, $objectID, $action, $comment = '')
    {
        $companyID  = $this->app->company->id;
        $actor      = $this->app->user->account;
        $actionDate = time();
        $sql = "INSERT INTO " . TABLE_ACTION . " VALUES('', '$companyID', '$objectType', '$objectID', '$actor', '$action', '$actionDate', '$comment')";
        $this->dbh->exec($sql);
        return $this->dbh->lastInsertID();
    }

    /* 返回某一个对象的所有action列表。*/
    public function getList($objectType, $objectID)
    {
        $actions = array();
        $sql = "SELECT * FROM " . TABLE_ACTION . " WHERE objectType = '$objectType' AND objectID = '$objectID' AND company = '{$this->app->company->id}' ORDER BY ID";
        $stmt = $this->dbh->query($sql);
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
}
