#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterCreate();
timeout=0
cid=0

- 执行bugTest模块的afterCreateTest方法，参数是$bug1, $params1  @1
- 执行$result2 && $afterCount >= $beforeCount @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug3, $params3  @1
- 执行bugTest模块的afterCreateTest方法，参数是$bug4, $params4  @1
- 执行$result5 && $todoStatus == 'done @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备execution测试数据
$execution = zenData('project');
$execution->id->range('101-103');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(3);

// 准备todo测试数据
$todo = zenData('todo');
$todo->id->range('1-10');
$todo->account->range('admin');
$todo->status->range('wait{5},doing{5}');
$todo->gen(10);

// 准备file测试数据,初始化为空
$file = zenData('file');
$file->gen(0);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 测试1:不带文件上传的普通bug创建后处理
$bug1 = new stdclass();
$bug1->id = 1;
$bug1->module = 100;
$bug1->execution = 0;
$params1 = array();
r($bugTest->afterCreateTest($bug1, $params1)) && p() && e('1');

// 测试2:带文件上传的bug创建后处理,验证文件是否保存
$_POST['fileList'] = json_encode(array(array('pathname' => '/tmp/test.png', 'title' => 'test image', 'extension' => 'png', 'size' => 1024)));
$bug2 = new stdclass();
$bug2->id = 2;
$bug2->module = 100;
$bug2->execution = 0;
$params2 = array();
$beforeCount = $tester->dao->select('count(*) as count')->from(TABLE_FILE)->fetch('count');
$result2 = $bugTest->afterCreateTest($bug2, $params2);
$afterCount = $tester->dao->select('count(*) as count')->from(TABLE_FILE)->fetch('count');
unset($_POST['fileList']);
r($result2 && $afterCount >= $beforeCount) && p() && e('1');

// 测试3:带execution和看板参数的bug创建后处理
$bug3 = new stdclass();
$bug3->id = 3;
$bug3->module = 100;
$bug3->execution = 101;
$params3 = array('laneID' => 1, 'columnID' => 1);
r($bugTest->afterCreateTest($bug3, $params3)) && p() && e('1');

// 测试4:带execution但无看板参数的bug创建后处理
$bug4 = new stdclass();
$bug4->id = 4;
$bug4->module = 100;
$bug4->execution = 101;
$params4 = array();
r($bugTest->afterCreateTest($bug4, $params4)) && p() && e('1');

// 测试5:带todoID参数的bug创建后处理,验证todo状态是否更新为done
$bug5 = new stdclass();
$bug5->id = 5;
$bug5->module = 100;
$bug5->execution = 0;
$params5 = array('todoID' => 1);
$result5 = $bugTest->afterCreateTest($bug5, $params5);
$todoStatus = $tester->dao->select('status')->from(TABLE_TODO)->where('id')->eq(1)->fetch('status');
r($result5 && $todoStatus == 'done') && p() && e('1');