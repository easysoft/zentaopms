<?php
/**
 *  此工具用来计算每个项目的延烧图。
 *  
 *  该工具为临时工具，后面会有更加完备的解决方案。使用之前，请修改对应的参数。
 *  运行方式：
 *      windows: php.exe computeburn.php，将php.exe换成实际的php.exe的安装目录。
 *      linux:   php computeburn.php，如果找不到php的可执行文件，加上相应的路径。
 */
/* 参数设置。*/
$dbHost     = 'localhost';
$dbUser     = 'root';
$dbPassword = '';
$dbName     = 'zentao';
$dbPrefix   = 'zt_';

/* 用到的表。*/
$tableProject = $dbPrefix . 'project';
$tableTask    = $dbPrefix . 'task';
$tableBurn    = $dbPrefix . 'burn';

/* 连接到数据库。*/
mysql_connect($dbHost, $dbUser, $dbPassword);
mysql_select_db($dbName);

/* 查找所有的项目。*/
$result = mysql_query("SELECT id FROM $tableProject WHERE end >= CURRENT_DATE() OR end = '0000-00-00'");
while($row = mysql_fetch_assoc($result)) $projects[] = $row['id'];

/* 计算burndown。*/
$date = date('Y-m-d');
$sql  = "SELECT project, sum(`left`) AS totalLeft, SUM(consumed) AS totalConsumed FROM $tableTask 
         WHERE project IN(" . join(',', $projects) . ') AND status !="cancel" GROUP BY project';
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_assoc($result))
{
    $sql = "REPLACE INTO $tableBurn VALUES($row[project], '$date', $row[totalLeft], $row[totalConsumed])";
    mysql_query($sql);
}
?>
