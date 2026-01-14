#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::processCaseSteps();
timeout=0
cid=19014

- 执行$result1->steps[1] @打开系统
- 执行$result2->steps['1.1'] @输入账号
- 执行$result3->steps) == 0 && count($result3->expects) == 0 && count($result3->stepType) == 0 @1
- 执行$result4->steps) == 0 && count($result4->expects) == 0 && count($result4->stepType) == 0 @1
- 执行$result5->steps['2.2'] @组内项目2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseModelTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：包含stepList的testcase对象正常转换
$case1 = new stdclass();
$testcase1 = new stdclass();
$testcase1->stepList = array(
    array('tmpId' => 'step1', 'tmpPId' => '', 'desc' => '打开系统', 'expect' => '系统正常打开', 'type' => 'step'),
    array('tmpId' => 'step2', 'tmpPId' => '', 'desc' => '输入用户名', 'expect' => '输入成功', 'type' => 'item')
);
$result1 = $testcaseTest->processCaseStepsTest($case1, $testcase1);
r($result1->steps[1]) && p() && e('打开系统');

// 步骤2：包含父子步骤层级结构的stepList
$case2 = new stdclass();
$testcase2 = new stdclass();
$testcase2->stepList = array(
    array('tmpId' => 'parent1', 'tmpPId' => '', 'desc' => '登录模块', 'expect' => '', 'type' => 'group'),
    array('tmpId' => 'child1', 'tmpPId' => 'parent1', 'desc' => '输入账号', 'expect' => '账号输入成功', 'type' => 'item'),
    array('tmpId' => 'child2', 'tmpPId' => 'parent1', 'desc' => '输入密码', 'expect' => '密码输入成功', 'type' => 'item')
);
$result2 = $testcaseTest->processCaseStepsTest($case2, $testcase2);
r($result2->steps['1.1']) && p() && e('输入账号');

// 步骤3：不包含stepList的testcase对象
$case3 = new stdclass();
$testcase3 = new stdclass();
$result3 = $testcaseTest->processCaseStepsTest($case3, $testcase3);
r(count($result3->steps) == 0 && count($result3->expects) == 0 && count($result3->stepType) == 0) && p() && e('1');

// 步骤4：空的stepList数组
$case4 = new stdclass();
$testcase4 = new stdclass();
$testcase4->stepList = array();
$result4 = $testcaseTest->processCaseStepsTest($case4, $testcase4);
r(count($result4->steps) == 0 && count($result4->expects) == 0 && count($result4->stepType) == 0) && p() && e('1');

// 步骤5：包含完整字段信息的复杂stepList
$case5 = new stdclass();
$testcase5 = new stdclass();
$testcase5->stepList = array(
    array('tmpId' => 'step1', 'tmpPId' => '', 'desc' => '第一步操作', 'expect' => '第一步期望', 'type' => 'step'),
    array('tmpId' => 'group1', 'tmpPId' => '', 'desc' => '测试组', 'expect' => '', 'type' => 'group'),
    array('tmpId' => 'item1', 'tmpPId' => 'group1', 'desc' => '组内项目1', 'expect' => '组内期望1', 'type' => 'item'),
    array('tmpId' => 'item2', 'tmpPId' => 'group1', 'desc' => '组内项目2', 'expect' => '组内期望2', 'type' => 'item')
);
$result5 = $testcaseTest->processCaseStepsTest($case5, $testcase5);
r($result5->steps['2.2']) && p() && e('组内项目2');