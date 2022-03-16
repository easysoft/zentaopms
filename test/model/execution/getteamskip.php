#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getTeamSkipTest();
cid=1
pid=1

正常跳过 >> 901,admin,研发
begin与end一致 >> 无跳转数据
end与begin一致 >> 无跳转数据

*/

$taskID = 901;
$begin  = 'admin';
$end    = 'user92';

$execution = new executionTest();
r($execution->getTeamSkipTest($taskID, $begin, $end))   && p('admin:root,account,role') && e('901,admin,研发'); // 正常跳过
r($execution->getTeamSkipTest($taskID, $begin, $begin)) && p()                        && e('无跳转数据');     // begin与end一致
r($execution->getTeamSkipTest($taskID, $end, $end))     && p()                        && e('无跳转数据');     // end与begin一致