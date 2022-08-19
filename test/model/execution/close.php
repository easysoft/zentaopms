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
子瀑布关闭获取父瀑布状态 >> closed

*/

$executionIDList = array('109', '138', '168', '708');
$noRealEnd       = array('realEnd' => '');

$execution = new executionTest();
r($execution->closeTest($executionIDList[0]))                && p('0:field,old,new') && e('status,wait,closed');    // 敏捷执行关闭
r($execution->closeTest($executionIDList[1]))                && p('0:field,old,new') && e('status,doing,closed');   // 瀑布执行关闭
r($execution->closeTest($executionIDList[2]))                && p('0:field,old,new') && e('status,doing,closed');   // 看板执行关闭
r($execution->closeTest($executionIDList[1],$noRealEnd))     && p('realEnd:0')       && e('『realEnd』不能为空。'); // 不输入实际完成时间校验
r($execution->closeTest($executionIDList[3], array(), true)) && p('status')          && e('closed');                // 子瀑布关闭获取父瀑布状态
$db->restoreDB();
