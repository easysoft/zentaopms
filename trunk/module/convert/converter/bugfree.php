<?php
class bugfreeConvertModel extends convertModel
{
    public function __construct()
    {
        parent::__construct();
        parent::connectDB();
    }

    /* 检查Tables。*/
    public function checkTables()
    {
        return true;
    }

    /* 检查安装路径。*/
    public function checkRoot()
    {
        return true;
    }

    /* 执行转换。*/
    public function execute()
    {
        $this->clear();
        $this->convertUser();
    }

    public function convertUser()
    {
        /* 查询当前系统中存在的用户。*/
        $activeUsers = $this->dao
            ->dbh($this->sourceDBH)
            ->select("{$this->app->company->id} AS company, username AS account, userpassword AS password, realname, email")
            ->from('BugUser')->fetchAll('account');

        /* 查找曾经出现过的用户。*/
        $allUsers = $this->dao->select("distinct(username) AS account")->from('BugHistory')->fetchPairs();

        /* 合并二者。*/
        foreach($allUsers as $key => $account)
        {
            if(isset($activeUsers[$account])) 
            {
                $allUsers[$key] = $activeUsers[$account];
            }
            else
            {
                $allUsers[$key] = array('company' => $this->app->company->id, 'account' => $account, 'status' => 'delete');
            }
        }
        foreach($activeUsers as $account => $user) if(!isset($allUsers[$account])) $allUsers[$account] = $user;
        foreach($allUsers as $user) $this->dao->dbh($this->dbh)->insert(TABLE_USER)->data($user)->exec();
    }

    public function convertProject()
    {
        global $myLink, $companyID;
        $sql    = "SELECT * FROM BugProject";
        $result = mysql_query($sql, $myLink);
        while($project = mysql_fetch_assoc($result))
        {
            extract($project);
            $sql = "INSERT INTO zt_product(id, name, company) values('$ProjectID', '$ProjectName', '$companyID')";
            mysql_query($sql) or die(mysql_error());
        }
    }

    public function convertModule()
    {
        global $myLink, $companyID;
        $sql    = "SELECT * FROM BugModule";
        $result = mysql_query($sql, $myLink);
        while($module = mysql_fetch_assoc($result))
        {
            extract($module);
            $sql = "INSERT INTO zt_module(id, product, name, parent, grade, view) values($ModuleID, $ProjectID, '$ModuleName', $ParentID, $ModuleGrade, 'bug')";
            mysql_query($sql) or die(mysql_error());
        }
    }

    public function convertBug()
    {
        global $myLink, $companyID;
        $sql    = "SELECT * FROM BugInfo";
        $result = mysql_query($sql, $myLink);
        while($bug = mysql_fetch_assoc($result))
        {
            foreach($bug as $key => $value)
            {
                if(strpos($key, 'Date')) $bug[$key] = strtotime($value);
            }
            extract($bug);
            $sql = "INSERT INTO zt_bug(id, product, module, title, severity, type, os,status, mailto, 
                openedby, openedDate,openedBuild, assignedTo,assignedDate, 
                resolvedBy, resolution, resolvedBuild, resolvedDate, 
                closedBy, closedDate, lastEditedBy, lastEditedDate
            ) values($BugID, '$ProjectID', '$ModuleID', '$BugTitle', '$BugSeverity', '$BugType', '$BugOS', '$BugStatus', '$MailTo',
                '$OpenedBy', '$OpenedDate', '$OpenedBuild', '$AssignedTo', '$AssignedDate',
                '$ResolvedBy', '$Resolution', '$ResolvedBuild', '$ResolvedDate',
                '$ClosedBy', '$ClosedDate', '$LastEditedBy', '$LastEditedDate')";
            mysql_query($sql) or die(mysql_error());
        }
    }

    public function convertAction()
    {
        global $myLink, $companyID;
        $sql    = "SELECT * FROM BugHistory ORDER BY BugID, HistoryID";
        $result = mysql_query($sql, $myLink);
        while($history = mysql_fetch_assoc($result))
        {
            $historys[$history['BugID']][] = $history;
        }
        foreach($historys as $bugID => $bugHistorys)
        {
            foreach($bugHistorys as $key => $history)
            {
                $history['FullInfo']   = addslashes($history['FullInfo']);
                $history['ActionDate'] = strtotime($history['ActionDate']);
                if($key == 0)
                {
                    $sql = "UPDATE zt_bug SET steps = \"$history[FullInfo]\" WHERE id='$bugID'";
                    mysql_query($sql) or die(mysql_error());
                    $history['FullInfo'] = '';
                }

                extract($history);
                $sql = "INSERT INTO zt_action values($HistoryID, $companyID, 'bug', $BugID, '$UserName', '$Action', $ActionDate, '$FullInfo')";
                mysql_query($sql) or die(mysql_error());
            }
        }

    }

    public function fixModulePath()
    { 
        global $myLink, $companyID;

        $sql    = "SELECT * FROM zt_module ORDER BY grade";
        $result = mysql_query($sql, $myLink);
        while($module = mysql_fetch_assoc($result))
        {
            if($module['grade'] == 1)
            {
                $sql = "UPDATE zt_module set path = ',$module[id],' WHERE id=$module[id]";
                mysql_query($sql) or die(mysql_error());
            }
            else
            {
                $sql = "SELECT path FROM zt_module WHERE id = $module[parent]";
                $result2 = mysql_query($sql);
                $parent = mysql_fetch_assoc($result2);
                $sql = "UPDATE zt_module set path = '$parent[path]$module[id],' WHERE id=$module[id]";
                mysql_query($sql) or die(mysql_error());
            }
        }
    }
    public function clear()
    {
        global $myLink;
        $sqls[] = "TRUNCATE TABLE zt_user";
        $sqls[] = "TRUNCATE TABLE zt_product";
        $sqls[] = "TRUNCATE TABLE zt_module";
        $sqls[] = "TRUNCATE TABLE zt_bug";
        $sqls[] = "TRUNCATE TABLE zt_action";
    }
}
