#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->closeTest();
cid=1
pid=1

敏捷执行关闭 >> status,wait,closed
瀑布执行关闭 >> status,doing,closed
看板执行关闭 >> status,doing,closed
不输入实际完成时间校验 >> 『realEnd』不能为空。

*/

$executionIDList = array('109', '138', '168');
$noRealEnd       = array('realEnd' => '');

$execution = new executionTest();
r($execution->closeTest($executionIDList[0]))            && p('0:field,old,new') && e('status,wait,closed');    // 敏捷执行关闭
r($execution->closeTest($executionIDList[1]))            && p('0:field,old,new') && e('status,doing,closed');   // 瀑布执行关闭
r($execution->closeTest($executionIDList[2]))            && p('0:field,old,new') && e('status,doing,closed');   // 看板执行关闭
r($execution->closeTest($executionIDList[1],$noRealEnd)) && p('realEnd:0')       && e('『realEnd』不能为空。'); // 不输入实际完成时间校验
$db->restoreDB();