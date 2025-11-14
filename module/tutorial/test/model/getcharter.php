#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getCharter();
timeout=0
cid=19413

- 步骤1：正常获取Charter对象并验证id属性id @1
- 步骤2：验证Charter名称属性name @Test charter
- 步骤3：验证Charter状态属性status @wait
- 步骤4：验证Charter级别属性level @3
- 步骤5：验证Charter类别属性category @IPD

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getCharterTest()) && p('id') && e('1'); // 步骤1：正常获取Charter对象并验证id
r($tutorialTest->getCharterTest()) && p('name') && e('Test charter'); // 步骤2：验证Charter名称
r($tutorialTest->getCharterTest()) && p('status') && e('wait'); // 步骤3：验证Charter状态
r($tutorialTest->getCharterTest()) && p('level') && e('3'); // 步骤4：验证Charter级别
r($tutorialTest->getCharterTest()) && p('category') && e('IPD'); // 步骤5：验证Charter类别