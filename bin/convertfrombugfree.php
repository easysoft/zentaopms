<?php
/* ½« BugFree×ª»»µ½zentaopms¡£*/
$companyID = 1;
$myLink = mysql_connect('localhost', 'root', 'zentao');
mysql_select_db('Backyard');
mysql_query('SET NAMES UTF8', $myLink);
clear();
convertUser();
convertProject();
convertModule();
convertBug();
convertAction();
fixModulePath();

function convertUser()
{
    global $myLink, $companyID;
    $sql    = "SELECT * FROM BugUser";
    $result = mysql_query($sql, $myLink);
    while($user = mysql_fetch_assoc($result))
    {
        extract($user);
        $sql = "INSERT INTO zt_user(company, id, account, password, realname, email) values('$companyID', $UserID, '$UserName', '$UserPassword', '$RealName', '$Email')";
        mysql_query($sql) or die(mysql_error());
    }
    $sql = "SELECT OpenedBy AS UserName FROM BugInfo GROUP BY OpenedBy";
    $result = mysql_query($sql, $myLink);
    while($user = mysql_fetch_assoc($result))
    {
        extract($user);
        $sql = "SELECT * FROM zt_user WHERE account = '$UserName'";
        if(!mysql_fetch_row(mysql_query($sql)))
        {
            $sql = "INSERT INTO zt_user(company, account) values('$companyID', '$UserName')";
            mysql_query($sql) or die(mysql_error());
        }
    }
    $sql = "INSERT INTO zt_user(company, account) values('$companyID', 'liyp')";
    mysql_query($sql) or die(mysql_error());

}

function convertProject()
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

function convertModule()
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

function convertBug()
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

function convertAction()
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

function fixModulePath()
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
function clear()
{
    global $myLink;
    $sqls[] = "TRUNCATE TABLE zt_user";
    $sqls[] = "TRUNCATE TABLE zt_product";
    $sqls[] = "TRUNCATE TABLE zt_module";
    $sqls[] = "TRUNCATE TABLE zt_bug";
    $sqls[] = "TRUNCATE TABLE zt_action";
    foreach($sqls as $sql) mysql_query($sql, $myLink);
}
?>
