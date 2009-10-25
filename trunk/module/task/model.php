<?php
/**
 * The model file of task module of ZenTaoMS.
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
 * @package     task
 * @version     $Id: model.php 1418 2009-10-14 07:53:28Z wwccss $
 * @link        http://www.zentao.cn
 */
?>
<?php
class taskModel extends model
{
    /* 新增一个任务。*/
    public function create($projectID)
    {
        extract($_POST);
        $sql = "INSERT INTO " . TABLE_TASK . " (`name`, `project`, `story`, `owner`, `estimate`, `left`, `desc`) VALUES('$name', '$projectID', '$storyID', '$owner', '$estimate', '$estimate', '$desc')";
        return $this->dbh->exec($sql);
    }

    /* 更新一个任务。*/
    public function update($taskID)
    {
        extract($_POST);
        $sql = "UPDATE " . TABLE_TASK . " SET `name` = '$name', `story` = '$storyID', `owner` = '$owner', estimate = '$estimate', `consumed` = '$consumed', `left` = '$left', `status` = '$status', `desc` = '$desc' WHERE id = '$taskID' LIMIT 1";
        return $this->dbh->exec($sql);
    }
    
    /* 删除一个任务。*/
    public function delete($taskID)
    {
        $sql = "DELETE FROM " . TABLE_TASK . " WHERE id = '$taskID'";
        return $this->dbh->exec($sql);
    }

    /* 通过id获取一个任务信息。*/
    public function getById($taskID)
    {
        return $this->dbh->query("SELECT * FROM " . TABLE_TASK . " WHERE id = '$taskID'")->fetch();
    }
    
    /* 获得某一个项目的任务列表。*/
    public function getProjectTasks($projectID)
    {
        $tasks = array();
        $sql = "SELECT T1.*, T2.title AS storyTitle, T3.realname AS ownerRealName FROM " . TABLE_TASK . " AS T1 LEFT JOIN " . TABLE_STORY . " AS T2 ON T1.story = T2.id LEFT JOIN " . TABLE_USER . " AS T3 ON T1.owner = T3.account WHERE T1.project = '$projectID' ORDER BY T1.story";
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll();
    }

    /* 获得用户的任务列表。*/
    public function getUserTasks($account, $status = 'all')
    {
        $sql = "SELECT T1.*, T2.name AS projectName, T2.id AS projectID, T3.id AS storyID, T3.title AS storyTitle FROM " . TABLE_TASK . " AS T1 
                LEFT JOIN " .TABLE_PROJECT . " AS T2 ON T1.project = T2.id 
                LEFT JOIN " . TABLE_STORY  . " AS T3 ON T1.story = T3.id 
                WHERE T1.owner = '$account'";
        if($status != 'all') $sql .= " AND T1.status" . helper::dbIN($status);
        return $this->dbh->query($sql)->fetchAll();
    }

    /* 获得用户的任务id=>name列表。*/
    public function getUserTaskPairs($account, $status = 'all')
    {
        $tasks = array();
        $sql = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.owner')->eq($account);
        if($status != 'all') $sql->andwhere('t1.status')->in($status);
        $stmt = $sql->query();
        while($task = $stmt->fetch())
        {
            $tasks[$task->id] = $task->project . ' / ' . $task->name;
        }
        return $tasks;
    }
}
