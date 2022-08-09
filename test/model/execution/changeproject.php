#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
$db->switchDB();
su('admin');

/**

title=测试executionModel->changeProjectTest();
cid=1
pid=1

测试修改敏捷执行关联项目 >> 41
测试修改瀑布执行关联项目 >> ,11,131,
测试修改看板执行关联项目 >> 41

*/

$executionIDList  = array('101', '131', '161');
$oldProjectIDlist = array('11', '41', '71');
$newProjectIDlist = array('11', '41', '71');

$execution = new executionTest();
r($execution->changeProjectTest($newProjectIDlist[1],$oldProjectIDlist[0],$executionIDList[0])) && p('0:parent') && e('41');       // 测试修改敏捷执行关联项目
r($execution->changeProjectTest($newProjectIDlist[0],$oldProjectIDlist[1],$executionIDList[1])) && p('0:path')   && e(',11,131,'); // 测试修改瀑布执行关联项目
r($execution->changeProjectTest($newProjectIDlist[1],$oldProjectIDlist[2],$executionIDList[2])) && p('0:parent') && e('41');       // 测试修改看板执行关联项目

$db->restoreDB();