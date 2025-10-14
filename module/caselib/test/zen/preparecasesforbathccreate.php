#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::prepareCasesForBathcCreate();
timeout=0
cid=0

- 步骤1：正常libID测试 - 返回3个用例 @3
- 步骤2：不同libID测试 - 检查第一个用例的lib字段第0条的lib属性 @2
- 步骤3：边界值测试 - 检查第一个用例的lib字段第0条的lib属性 @0
- 步骤4：必填字段验证 - 检查返回空数组 @0
- 步骤5：默认值设置测试 - 检查返回2个用例 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 准备测试数据
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2');
$user->password->range('123456{10}');
$user->gen(3);

$caselib = zenData('case');
$caselib->id->range('1-10');
$caselib->lib->range('1-3');
$caselib->title->range('测试用例{1-10}');
$caselib->type->range('feature,interface,performance,config');
$caselib->gen(10);

// 模拟用户登录
su('admin');

// 设置POST数据来模拟form::batchData
global $_POST;
$_POST['title'] = array('批量创建用例1', '批量创建用例2', '批量创建用例3');
$_POST['type'] = array('feature', 'interface', 'performance');
$_POST['pri'] = array(1, 2, 3);
$_POST['module'] = array(1, 2, 1);
$_POST['precondition'] = array('前置条件1', '前置条件2', '');
$_POST['keywords'] = array('关键词1', '关键词2', '关键词3');
$_POST['stage'] = array('unittest', 'feature', 'intergrate');

$caselibTest = new caselibTest();

r($caselibTest->prepareCasesForBathcCreateTest(1)) && p('') && e('3'); // 步骤1：正常libID测试 - 返回3个用例
r($caselibTest->prepareCasesForBathcCreateTest(2)) && p('0:lib') && e('2'); // 步骤2：不同libID测试 - 检查第一个用例的lib字段
r($caselibTest->prepareCasesForBathcCreateTest(0)) && p('0:lib') && e('0'); // 步骤3：边界值测试 - 检查第一个用例的lib字段

// 测试必填字段验证 - 清空title
$_POST['title'] = array('', '', '');
r($caselibTest->prepareCasesForBathcCreateTest(1)) && p('') && e('0'); // 步骤4：必填字段验证 - 检查返回空数组

// 重置数据测试默认值
$_POST['title'] = array('默认测试用例1', '默认测试用例2');
$_POST['type'] = array('', '');
$_POST['pri'] = array('', '');
r($caselibTest->prepareCasesForBathcCreateTest(1)) && p('') && e('2'); // 步骤5：默认值设置测试 - 检查返回2个用例