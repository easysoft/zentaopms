#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->processBuildForBugs();
cid=1
pid=1

测试处理bug1 2 3的openedBuild resolvedBuild字段 >> 项目版本版本1,;0,;项目版本版本1,
测试处理bug4 5 6的openedBuild resolvedBuild字段 >> 主干,;主干,;主干,
测试处理bug51 52 53的openedBuild resolvedBuild字段 >> 主干,;主干,;主干,
测试处理bug54 55 56的openedBuild resolvedBuild字段 >> 主干,;主干,;主干,主干
测试处理bug81 82 83的openedBuild resolvedBuild字段 >> 主干,;主干,;主干,
测试处理bug84 85 86的openedBuild resolvedBuild字段 >> 主干,;主干,;主干,

*/

$bugIDList1 = array('1', '2', '3');
$bugIDList2 = array('4', '5', '6');
$bugIDList3 = array('51', '52', '53');
$bugIDList4 = array('54', '55', '56');
$bugIDList5 = array('81', '82', '83');
$bugIDList6 = array('84', '85', '86');

$bug=new bugTest();
r($bug->processBuildForBugsTest($bugIDList1)) && p('1:openedBuild,resolvedBuild;2:openedBuild,resolvedBuild;3:openedBuild,resolvedBuild')    && e('项目版本版本1,;0,;项目版本版本1,'); // 测试处理bug1 2 3的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList2)) && p('4:openedBuild,resolvedBuild;5:openedBuild,resolvedBuild;6:openedBuild,resolvedBuild')    && e('主干,;主干,;主干,');        // 测试处理bug4 5 6的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList3)) && p('51:openedBuild,resolvedBuild;52:openedBuild,resolvedBuild;53:openedBuild,resolvedBuild') && e('主干,;主干,;主干,');        // 测试处理bug51 52 53的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList4)) && p('54:openedBuild,resolvedBuild;55:openedBuild,resolvedBuild;56:openedBuild,resolvedBuild') && e('主干,;主干,;主干,主干');    // 测试处理bug54 55 56的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList5)) && p('81:openedBuild,resolvedBuild;82:openedBuild,resolvedBuild;83:openedBuild,resolvedBuild') && e('主干,;主干,;主干,');        // 测试处理bug81 82 83的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList6)) && p('84:openedBuild,resolvedBuild;85:openedBuild,resolvedBuild;86:openedBuild,resolvedBuild') && e('主干,;主干,;主干,');        // 测试处理bug84 85 86的openedBuild resolvedBuild字段