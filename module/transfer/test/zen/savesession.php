#!/usr/bin/env php
<?php

/**

title=测试 transferZen::saveSession();
timeout=0
cid=19342

- 步骤1：正常参数解析
 - 属性executionID @1
 - 属性status @open
- 步骤2：空参数情况 @0
- 步骤3：单个参数属性productID @5
- 步骤4：多个参数
 - 属性productID @3
 - 属性branch @2
 - 属性module @10
- 步骤5：包含下划线的参数
 - 属性name @test_case
 - 属性type @feature

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($transferTest->saveSessionTest('task', 'executionID=1,status=open')) && p('executionID,status') && e('1,open'); // 步骤1：正常参数解析
r($transferTest->saveSessionTest('project', '')) && p() && e('0'); // 步骤2：空参数情况
r($transferTest->saveSessionTest('bug', 'productID=5')) && p('productID') && e('5'); // 步骤3：单个参数
r($transferTest->saveSessionTest('story', 'productID=3,branch=2,module=10')) && p('productID,branch,module') && e('3,2,10'); // 步骤4：多个参数
r($transferTest->saveSessionTest('testcase', 'name=test_case,type=feature')) && p('name,type') && e('test_case,feature'); // 步骤5：包含下划线的参数