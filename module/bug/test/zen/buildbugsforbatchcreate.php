#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugsForBatchCreate();
timeout=0
cid=0

- 执行$result @2
- 执行$result[0]) ? $result[0]->product : 0 @1
- 执行$result[0]) ? $result[0]->openedBy :  @admin
- 执行$result[1]) ? $result[1]->assignedTo :  @admin
- 执行bugTest模块的buildBugsForBatchCreateTest方法，参数是1, ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 准备产品数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('Product{1-3}');
$product->type->range('normal{2},branch{1}');
$product->status->range('normal');
$product->gen(3);

// 准备模块数据
$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1{3},2{2}');
$module->name->range('Module{1-5}');
$module->type->range('bug');
$module->owner->range('admin{2},user1{2},user2{1}');
$module->parent->range('0');
$module->path->range('`,1,`{1},`,2,`{1},`,3,`{1},`,4,`{1},`,5,`{1}');
$module->gen(5);

// 准备用户数据
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->password->range('123456{5}');
$user->gen(5);

// 模拟批量创建的表单数据
$_POST['title'] = array('Bug Title 1', 'Bug Title 2');
$_POST['type'] = array('codeerror', 'designdefect'); 
$_POST['severity'] = array(3, 2);
$_POST['pri'] = array(3, 2);
$_POST['module'] = array(1, 2);
$_POST['steps'] = array('Step 1 content', 'Step 2 content');
$_POST['uploadImage'] = array('', '');

// 用户登录
su('admin');

// 创建测试实例
$bugTest = new bugTest();

// 测试步骤1：正常产品和分支参数
$result = $bugTest->buildBugsForBatchCreateTest(1, '');
r(count($result)) && p() && e('2');

// 测试步骤2：检查第一个bug的产品ID
r(isset($result[0]) ? $result[0]->product : 0) && p() && e('1');

// 测试步骤3：检查第一个bug的开启人
r(isset($result[0]) ? $result[0]->openedBy : '') && p() && e('admin');

// 测试步骤4：检查第二个bug的模块分配
r(isset($result[1]) ? $result[1]->assignedTo : '') && p() && e('admin');

// 测试步骤5：清空表单数据测试
$_POST = array();
r(count($bugTest->buildBugsForBatchCreateTest(1, ''))) && p() && e('0');