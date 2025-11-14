#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildBatchEditView();
timeout=0
cid=19295

- 步骤1：正常调用属性result @success
- 步骤2：验证customFields属性customFields @1
- 步骤3：验证showFields属性showFields @1
- 步骤4：验证times属性times @1
- 步骤5：验证title属性title @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备
zendata('config')->loadYaml('config_buildbatcheditview', false, 2)->gen(5);
zendata('user')->loadYaml('user_buildbatcheditview', false, 2)->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$todoTest = new todoTest();

// 5. 执行测试步骤
r($todoTest->buildBatchEditViewTest()) && p('result') && e('success'); // 步骤1：正常调用
r($todoTest->buildBatchEditViewTest()) && p('customFields') && e('1'); // 步骤2：验证customFields
r($todoTest->buildBatchEditViewTest()) && p('showFields') && e('1'); // 步骤3：验证showFields
r($todoTest->buildBatchEditViewTest()) && p('times') && e('1'); // 步骤4：验证times
r($todoTest->buildBatchEditViewTest()) && p('title') && e('1'); // 步骤5：验证title