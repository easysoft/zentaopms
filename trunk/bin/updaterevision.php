#!/usr/bin/env php
<?php
/* 此工具用来获取最新的svn信息。*/
$svnInfo  = `cd ../; svn info --xml |egrep 'revision|date'`;
$svnInfo  = explode("\n", trim($svnInfo));
$revision = $svnInfo[0];
$date     = $svnInfo[2];
preg_match('|"(.*)"|', $revision, $result);
$revision = $result[1];
preg_match('|>(.*)<|', $date, $result);
$date = $result[1];
$date = date('Y-m-d H:i:s', strtotime($date));
file_put_contents('../cache/revision.txt', "$revision\n$date");
?>
