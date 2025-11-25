#!/usr/bin/env php
<?php

/**

title=测试 transferZen::process4Testcase();
timeout=0
cid=19339

- 执行transferTest模块的process4TestcaseTest方法，参数是'stepDesc', $testData1, 0  @312
- 执行transferTest模块的process4TestcaseTest方法，参数是'precondition', $testData1, 0  @127
- 执行transferTest模块的process4TestcaseTest方法，参数是'stepExpect', $testData1, 0  @0
- 执行transferTest模块的process4TestcaseTest方法，参数是'invalidField', $testData1, 0  @312
- 执行transferTest模块的process4TestcaseTest方法，参数是'stepDesc', array  @312

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. zendata数据准备
$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->password->range('123456{5}');
$user->role->range('admin{1},dev{3},qa{1}');
$user->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$transferTest = new transferZenTest();

// 5. 测试步骤

// 构造测试数据
$testData1 = array(
    0 => (object)array(
        'id' => 1,
        'title' => '测试用例1',
        'precondition' => '前置条件测试'
    )
);

// 测试步骤1：stepDesc字段处理，返回HTML表格
r(strlen($transferTest->process4TestcaseTest('stepDesc', $testData1, 0))) && p() && e('312');

// 测试步骤2：precondition字段处理，返回textarea
r(strlen($transferTest->process4TestcaseTest('precondition', $testData1, 0))) && p() && e('127');

// 测试步骤3：stepExpect字段处理，直接返回空
r(strlen($transferTest->process4TestcaseTest('stepExpect', $testData1, 0))) && p() && e('0');

// 测试步骤4：无效字段处理，返回默认表格
r(strlen($transferTest->process4TestcaseTest('invalidField', $testData1, 0))) && p() && e('312');

// 测试步骤5：空数据处理，返回默认表格
r(strlen($transferTest->process4TestcaseTest('stepDesc', array(), 0))) && p() && e('312');