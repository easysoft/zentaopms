#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::fetchPivotDrills();
timeout=0
cid=17438

- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是1, '1', 'status' 第status条的field属性 @status
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是1, '1', array  @2
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是999, '1', 'status'  @0
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是1, '999', 'status'  @0
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是1, '1', 'notexistfield'  @0
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是2, '1', 'status' 第status条的pivot属性 @2
- 执行pivotTest模块的fetchPivotDrillsTest方法，参数是1, '1', 'status' 第status条的object属性 @bug

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

global $tester;
$tester->dao->exec("DELETE FROM " . TABLE_PIVOTDRILL);
$tester->dao->exec("INSERT INTO " . TABLE_PIVOTDRILL . " (`pivot`, `version`, `field`, `object`, `whereSql`, `condition`, `status`, `account`, `type`) VALUES
    (1, '1', 'status', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (1, '1', 'priority', 'bug', 'WHERE priority > 1', '{\"priority\":\"high\"}', 'published', 'admin', 'manual'),
    (2, '1', 'status', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (3, '1', 'status', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (4, '1', 'priority', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (5, '1', 'priority', 'bug', 'WHERE status = ''active''', '{\"status\":\"active\"}', 'published', 'admin', 'manual'),
    (1, '2', 'type', 'task', 'WHERE priority > 1', '{\"priority\":\"high\"}', 'published', 'user1', 'manual'),
    (2, '2', 'type', 'task', 'WHERE priority > 1', '{\"priority\":\"high\"}', 'published', 'user1', 'manual'),
    (3, '2', 'assignedTo', 'task', 'WHERE priority > 1', '{\"priority\":\"high\"}', 'published', 'user1', 'auto'),
    (4, '3', 'assignedTo', 'story', 'WHERE type = ''bug''', '{\"type\":\"bug\"}', 'design', 'user2', 'auto')");

$pivotTest = new pivotTaoTest();

r($pivotTest->fetchPivotDrillsTest(1, '1', 'status')) && p('status:field') && e('status');
r(count($pivotTest->fetchPivotDrillsTest(1, '1', array('status', 'priority')))) && p() && e('2');
r(count($pivotTest->fetchPivotDrillsTest(999, '1', 'status'))) && p() && e('0');
r(count($pivotTest->fetchPivotDrillsTest(1, '999', 'status'))) && p() && e('0');
r(count($pivotTest->fetchPivotDrillsTest(1, '1', 'notexistfield'))) && p() && e('0');
r($pivotTest->fetchPivotDrillsTest(2, '1', 'status')) && p('status:pivot') && e('2');
r($pivotTest->fetchPivotDrillsTest(1, '1', 'status')) && p('status:object') && e('bug');