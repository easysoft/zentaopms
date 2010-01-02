<?php
/* 此工具用来计算每个项目的burndown。*/
mysql_connect('localhost', 'root', '');
mysql_select_db('zentao');

$result = mysql_query("SELECT id FROM zt_project WHERE end >= CURRENT_DATE() or end = '0000-00-00'");
while($row = mysql_fetch_assoc($result))
{
    $projects[] = $row['id'];
}

$date = date('Y-m-d');
$sql = "SELECT project, sum(`left`) as totalLeft, sum(consumed) as totalConsumed FROM zt_task WHERE project in(" . join(',', $projects) . ') and status !="cancel" group by project';
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_assoc($result))
{
    $sql = "REPLACE INTO zt_burn values($row[project], '$date', $row[totalLeft], $row[totalConsumed])";
    mysql_query($sql);
}
?>
