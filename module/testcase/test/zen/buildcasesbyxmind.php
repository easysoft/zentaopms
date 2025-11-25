#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCasesByXmind();
timeout=0
cid=19077

- 步骤1：正常情况
 - 第0条的title属性 @新测试用例
 - 第0条的module属性 @1
 - 第0条的product属性 @1
- 步骤2：空用例列表 @0
- 步骤3：插入操作第0条的status属性 @normal
- 步骤4：更新操作有变化
 - 第0条的title属性 @更新用例
 - 第0条的id属性 @1
- 步骤5：更新操作无变化
 - 第0条的title属性 @无变化用例
 - 第0条的id属性 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{5}');
$product->status->range('normal{5}');
$product->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->parent->range('0{5},1-5');
$module->name->range('模块1,模块2,模块3,模块4,模块5,子模块1,子模块2,子模块3,子模块4,子模块5');
$module->type->range('case{10}');
$module->root->range('1{10}');
$module->gen(10);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1{10}');
$case->module->range('1-10');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$case->version->range('1{5},2{5}');
$case->status->range('normal{10}');
$case->gen(10);

$casestep = zenData('casestep');
$casestep->case->range('1-10');
$casestep->version->range('1{5},2{5}');
$casestep->type->range('step{10}');
$casestep->desc->range('步骤1,步骤2,步骤3,步骤4,步骤5,步骤6,步骤7,步骤8,步骤9,步骤10');
$casestep->expect->range('期望1,期望2,期望3,期望4,期望5,期望6,期望7,期望8,期望9,期望10');
$casestep->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcasezenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->buildCasesByXmindTest(1, '0', array(array('id' => 0, 'module' => 1, 'name' => '新测试用例', 'pri' => 3, 'precondition' => '前置条件', 'tmpPId' => '', 'steps' => array(), 'expects' => array())), true)) && p('0:title,module,product') && e('新测试用例,1,1'); // 步骤1：正常情况
r($testcaseTest->buildCasesByXmindTest(1, '0', array(), false)) && p() && e('0'); // 步骤2：空用例列表
r($testcaseTest->buildCasesByXmindTest(1, '0', array(array('id' => 0, 'module' => 1, 'name' => '插入用例', 'pri' => 2, 'precondition' => '', 'tmpPId' => '', 'steps' => array(), 'expects' => array())), true)) && p('0:status') && e('normal'); // 步骤3：插入操作
r($testcaseTest->buildCasesByXmindTest(1, '0', array(array('id' => 1, 'module' => 1, 'name' => '更新用例', 'pri' => 1, 'precondition' => '更新前置', 'tmpPId' => '', 'steps' => array('新步骤'), 'expects' => array('新期望'))), false)) && p('0:title,id') && e('更新用例,1'); // 步骤4：更新操作有变化
r($testcaseTest->buildCasesByXmindTest(1, '0', array(array('id' => 2, 'module' => 2, 'name' => '无变化用例', 'pri' => 3, 'precondition' => '', 'tmpPId' => '', 'steps' => array(), 'expects' => array())), false)) && p('0:title,id') && e('无变化用例,2'); // 步骤5：更新操作无变化