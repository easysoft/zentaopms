#!/usr/bin/env php
<?php

/**

title=测试 jenkinsZen::buildTree();
timeout=0
cid=0

- 步骤1：空数组测试 @0
- 步骤2：单个字符串测试第0条的text属性 @task1
- 步骤3：嵌套数组结构第0条的type属性 @folder
- 步骤4：URL解码测试第0条的text属性 @task name
- 步骤5：多个字符串测试第1条的text属性 @task2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/jenkins.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$jenkinsTest = new jenkinsTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($jenkinsTest->buildTreeTest(array())) && p() && e('0'); // 步骤1：空数组测试
r($jenkinsTest->buildTreeTest(array('task1'))) && p('0:text') && e('task1'); // 步骤2：单个字符串测试
r($jenkinsTest->buildTreeTest(array('folder1' => array('subtask1')))) && p('0:type') && e('folder'); // 步骤3：嵌套数组结构
r($jenkinsTest->buildTreeTest(array('task%20name'))) && p('0:text') && e('task name'); // 步骤4：URL解码测试
r($jenkinsTest->buildTreeTest(array('task1', 'task2'))) && p('1:text') && e('task2'); // 步骤5：多个字符串测试