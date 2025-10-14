#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::prepareReviewData();
timeout=0
cid=0

- 步骤1：正常情况属性status @normal
- 步骤2：边界值属性status @blocked
- 步骤3：异常输入属性result @必须选择评审结果
- 步骤4：权限验证
 - 属性reviewedBy @admin
- 步骤5：业务规则属性id @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('case')->loadYaml('case_preparereviewdata', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 强制要求：必须包含至少5个测试步骤
$_POST['result'] = 'pass';
$_POST['reviewedBy'] = array('admin', 'user1');
$_POST['comment'] = '评审通过';
$_POST['uid'] = 'test123';

$oldCase = new stdclass();
$oldCase->status = 'wait';

r($testcaseTest->prepareReviewDataTest(1, $oldCase)) && p('status') && e('normal'); // 步骤1：正常情况

$_POST['result'] = 'fail';
$oldCase->status = 'blocked';

r($testcaseTest->prepareReviewDataTest(2, $oldCase)) && p('status') && e('blocked'); // 步骤2：边界值

unset($_POST['result']);

r($testcaseTest->prepareReviewDataTest(3, $oldCase)) && p('result') && e('必须选择评审结果'); // 步骤3：异常输入

$_POST['result'] = 'pass';
$_POST['reviewedBy'] = array('admin', 'user1', 'user2');

r($testcaseTest->prepareReviewDataTest(4, $oldCase)) && p('reviewedBy') && e('admin,user1,user2'); // 步骤4：权限验证

$_POST['result'] = 'pass';
$_POST['comment'] = '评审通过';

r($testcaseTest->prepareReviewDataTest(5, $oldCase)) && p('id') && e('5'); // 步骤5：业务规则