<?php
/**
 * The model file of bug module of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class bugModel extends model
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
    }

    /* 创建一个Bug。*/
    function create()
    {
        extract($_POST);
        $openedBy     = $this->app->user->account;
        $openedDate   = time();
        $assignedDate = !empty($assignedTo) ? time() : 0;
        $sql = "INSERT INTO " . TABLE_BUG . " (product, module, type, severity, os, browser, assignedTo, assignedDate, mailTo, title, steps, openedBy, openedDate) 
                VALUES('$productID', '$moduleID', '$type', '$severity', '$os', '$browser', '$assignedTo', '$assignedDate', '$mailTo', '$title', '$steps', '$openedBy', '$openedDate' )";
        $this->dbh->exec($sql);
        return $this->dbh->lastInsertID();
    }

    /* 获得某一个产品，某一个模块下面的所有bug。*/
    public function getModuleBugs($productID, $moduleIds = 0)
    {
        $where  = " WHERE `product` = '$productID'";
        $where .= !empty($moduleIds) ? " AND module " . helper::dbIN($moduleIds) : '';
        $sql    = "SELECT * FROM " . TABLE_BUG .  $where . " ORDER BY id DESC";
        $stmt   = $this->dbh->query($sql);
        return $stmt->fetchAll();
    }

    /* 获取一个bug的详细信息。*/
    public function getById($bugID)
    {
        $bug = $this->dbh->query("SELECT * FROM " . TABLE_BUG . " WHERE id = '$bugID'")->fetch();
        foreach($bug as $key => $value)
        {
            if(strpos($key, 'Date') !== false)
            {
                if(empty($value))
                {
                    $bug->$key = '';
                }
                else
                {
                    $bug->$key = date('Y-m-d H:i:s', $value);
                }
            }
        }
        return $bug;
    }

    /* 更新bug信息。*/
    public function update($bugID)
    {
        $bug     = $this->getById($bugID);
        $changes = array();
        foreach($_POST as $key => $value)
        {
            if($key == 'comment') continue;
            if(strpos($key, 'Date') !== false) $_POST[$key] = strtotime($value);
            if($key == 'severity') $value = str_replace('item', '', $value);
            if($value != $bug->$key)
            {
                $change['field'] = $key;
                $change['old']   = $bug->$key;
                $change['new']   = $value;
                $changes[] = $change;
            }
        }
        extract($_POST);
        $now            = time();
        $severity       = str_replace('item', '', $severity);
        $lastEditedDate = $now;
        $assignedDate   = $bug->assignedDate;

        if($assignedTo != $bug->assignedTo) $assignedDate = $now;
        if($resolution != '' and empty($resolvedDate)) $resolvedDate = $now;
        if($closedBy   != '' and empty($closedDate))   $closedDate   = $now;

        $sql = "UPDATE " . TABLE_BUG . " SET 
            title = '$title', product='$product', module = '$module', 
            type='$type', severity = '$severity', os = '$os', status = '$status', 
            assignedTo='$assignedTo', assignedDate = '$assignedDate', resolvedBy = '$resolvedBy', resolvedDate = '$resolvedDate', resolution='$resolution',
            closedBy = '$closedBy',  closedDate = '$closedDate', steps = '$steps', 
            lastEditedBy = '{$this->app->user->account}', lastEditedDate = '$lastEditedDate'
            WHERE id ='$bugID' LIMIT 1 ";
        $this->dbh->exec($sql);
        return $changes;
    }

    /* 从bug列表中提取所有出现过的账户。*/
    public function extractAccountsFromList($bugs)
    {
        $accounts = array();
        foreach($bugs as $bug)
        {
            if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
            if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
            if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
            if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
            if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        }
        return array_unique($accounts);
    }

    /* 从一条bug中提取所有出现过的账户。*/
    public function extractAccountsFromSingle($bug)
    {
        $accounts = array();
        if(!empty($bug->openedBy))     $accounts[] = $bug->openedBy;
        if(!empty($bug->assignedTo))   $accounts[] = $bug->assignedTo;
        if(!empty($bug->resolvedBy))   $accounts[] = $bug->resolvedBy;
        if(!empty($bug->closedBy))     $accounts[] = $bug->closedBy;
        if(!empty($bug->lastEditedBy)) $accounts[] = $bug->lastEditedBy;
        return array_unique($accounts);
    }

    /* 获得用户的Bug id=>title列表。*/
    public function getUserBugPairs($account)
    {
        $bugs = array();
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->query();
        while($bug = $stmt->fetch())
        {
            $bug->title = $bug->product . ' / ' . $bug->title;
            $bugs[$bug->id] = $bug->title;
        }
        return $bugs;
    }

    /* 获得某个项目的bug列表。*/
    public function getProjectBugs($projectID, $orderBy = 'id|desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BUG)->where('project')->eq((int)$projectID)->orderBy($orderBy)->page($pager)->fetchAll();
    }
}
