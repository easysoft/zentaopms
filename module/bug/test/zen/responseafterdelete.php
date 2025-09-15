#!/usr/bin/env php
<?php

/**

title=title=- 属性confirm @Bug
timeout=0
cid=9

- 步骤1：默认消息处理
 - 属性result @success
 - 属性message @保存成功
- 步骤2：JSON视图类型
 - 属性result @success
 - 属性message @保存成功
- 步骤3：bug转任务确认结果属性result @success
- 步骤4：弹窗模式
 - 属性result @success
 - 属性load @1
- 步骤5：任务看板删除
 - 属性result @success
 - 属性closeModal @1
 - 属性callback @refreshKanban()

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('bug');
$table->id->range('1-10');
$table->product->range('1{5}, 2{5}');
$table->toTask->range('0{8}, 100{1}, 101{1}');
$table->gen(10);

$taskTable = zenData('task');
$taskTable->id->range('100-101');
$taskTable->deleted->range('0{1}, 1{1}');
$taskTable->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 创建测试用的bug对象
$bug1 = new stdClass();
$bug1->id = 1;
$bug1->product = 1;
$bug1->toTask = null;

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->product = 1;
$bug2->toTask = null;

$bug3 = new stdClass();
$bug3->id = 9;
$bug3->product = 2;
$bug3->toTask = 100;

$bug4 = new stdClass();
$bug4->id = 4;
$bug4->product = 1;
$bug4->toTask = null;

$bug5 = new stdClass();
$bug5->id = 5;
$bug5->product = 1;
$bug5->toTask = null;

r($bugTest->responseAfterDeleteTest($bug1, 'product', '', array())) && p('result,message') && e('success,保存成功'); // 步骤1：默认消息处理
r($bugTest->responseAfterDeleteTest($bug2, 'product', '', array('viewType' => 'json'))) && p('result,message') && e('success,保存成功'); // 步骤2：JSON视图类型
r($bugTest->responseAfterDeleteTest($bug3, 'product', '', array())) && p('result') && e('success'); // 步骤3：bug转任务确认结果
r($bugTest->responseAfterDeleteTest($bug4, 'product', '', array('isInModal' => true))) && p('result,load') && e('success,1'); // 步骤4：弹窗模式
r($bugTest->responseAfterDeleteTest($bug5, 'taskkanban', '', array())) && p('result,closeModal,callback') && e('success,1,refreshKanban()'); // 步骤5：任务看板删除