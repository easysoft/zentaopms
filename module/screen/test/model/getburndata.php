#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getBurnData();
timeout=0
cid=18237

- 测试有进行中执行数据时返回数量 @4
- 测试执行名称拼接格式第10条的name属性 @项目1--迭代1
- 测试执行开始日期格式第10条的begin属性 @2025-01-01
- 测试执行状态为进行中第10条的status属性 @doing
- 测试执行类型为sprint第11条的type属性 @sprint

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 清空数据表
global $tester;
$tester->dao->exec('TRUNCATE TABLE ' . TABLE_PROJECT);

// 直接插入项目数据
$projects = array(
    array('id' => 1, 'name' => '项目1', 'type' => 'project', 'status' => 'doing', 'begin' => '2025-01-01', 'end' => '2025-03-01', 'parent' => 0, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 2, 'name' => '项目2', 'type' => 'project', 'status' => 'doing', 'begin' => '2025-02-01', 'end' => '2025-04-01', 'parent' => 0, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 3, 'name' => '项目3', 'type' => 'project', 'status' => 'done', 'begin' => '2025-03-01', 'end' => '2025-05-01', 'parent' => 0, 'deleted' => '0', 'vision' => 'rnd'),
);

foreach($projects as $project) {
    $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
}

// 直接插入执行数据
$executions = array(
    array('id' => 10, 'name' => '迭代1', 'type' => 'sprint', 'status' => 'doing', 'project' => 1, 'begin' => '2025-01-01', 'end' => '2025-02-01', 'parent' => 1, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 11, 'name' => '迭代2', 'type' => 'sprint', 'status' => 'doing', 'project' => 1, 'begin' => '2025-02-01', 'end' => '2025-03-01', 'parent' => 1, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 12, 'name' => '阶段1', 'type' => 'stage', 'status' => 'doing', 'project' => 2, 'begin' => '2025-01-01', 'end' => '2025-02-01', 'parent' => 2, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 13, 'name' => '阶段2', 'type' => 'stage', 'status' => 'doing', 'project' => 2, 'begin' => '2025-02-01', 'end' => '2025-03-01', 'parent' => 2, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 14, 'name' => '迭代3', 'type' => 'sprint', 'status' => 'wait', 'project' => 3, 'begin' => '2025-01-01', 'end' => '2025-02-01', 'parent' => 3, 'deleted' => '0', 'vision' => 'rnd'),
    array('id' => 15, 'name' => '迭代4', 'type' => 'sprint', 'status' => 'wait', 'project' => 3, 'begin' => '2025-02-01', 'end' => '2025-03-01', 'parent' => 3, 'deleted' => '0', 'vision' => 'rnd'),
);

foreach($executions as $execution) {
    $tester->dao->insert(TABLE_PROJECT)->data($execution)->exec();
}

su('admin');

$screenTest = new screenModelTest();

r(count($screenTest->getBurnDataTest())) && p() && e('4');                           // 测试有进行中执行数据时返回数量
r($screenTest->getBurnDataTest()) && p('10:name') && e('项目1--迭代1');              // 测试执行名称拼接格式
r($screenTest->getBurnDataTest()) && p('10:begin') && e('2025-01-01');              // 测试执行开始日期格式
r($screenTest->getBurnDataTest()) && p('10:status') && e('doing');                  // 测试执行状态为进行中
r($screenTest->getBurnDataTest()) && p('11:type') && e('sprint');                   // 测试执行类型为sprint