#!/usr/bin/env php
<?php
/**

title=测试 actionModel::getHistory();
timeout=0
cid=14901

- 测试步骤1：使用整数actionID查询存在的历史记录
 - 第0条的field属性 @resolution
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 测试步骤2：使用字符串actionID查询存在的历史记录
 - 第0条的field属性 @resolvedBuild
 - 第0条的old属性 @2
 - 第0条的new属性 @3
- 测试步骤3：查询没有历史记录的actionID @0
- 测试步骤4：查询不存在的actionID @0
- 测试步骤5：使用无效的actionID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;

// 直接插入测试数据
$tester->dao->exec("DELETE FROM zt_action WHERE id <= 10");
$tester->dao->exec("DELETE FROM zt_history WHERE action <= 10");

// 插入action数据
$tester->dao->exec("INSERT INTO zt_action (id, objectType, objectID, actor, action, date) VALUES
    (1, 'bug', 1, 'admin', 'opened', NOW()),
    (2, 'bug', 2, 'admin', 'closed', NOW()),
    (3, 'task', 1, 'user1', 'edited', NOW())");

// 插入history数据
$tester->dao->exec("INSERT INTO zt_history (id, action, field, old, new, diff) VALUES
    (1, 1, 'resolution', '1', '2', ''),
    (2, 2, 'resolvedBuild', '2', '3', ''),
    (3, 1, 'status', 'active', 'resolved', ''),
    (4, 2, 'status', 'resolved', 'closed', '')");

su('admin');

$actionTest = new actionModelTest();

r($actionTest->getHistoryTest(1)[1])   && p("0:field,old,new") && e('resolution,1,2');    // 测试步骤1：使用整数actionID查询存在的历史记录
r($actionTest->getHistoryTest('2')[2]) && p("0:field,old,new") && e('resolvedBuild,2,3'); // 测试步骤2：使用字符串actionID查询存在的历史记录
r($actionTest->getHistoryTest(3))      && p()                  && e('0');                 // 测试步骤3：查询没有历史记录的actionID
r($actionTest->getHistoryTest(10000))  && p()                  && e('0');                 // 测试步骤4：查询不存在的actionID
r($actionTest->getHistoryTest(0))      && p()                  && e('0');                 // 测试步骤5：使用无效的actionID
