#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::addPrefixToOrderBy();
timeout=0
cid=19153

- 步骤1：特殊字段assignedTo @t1.assignedTo
- 步骤2：特殊字段status倒序 @t1.status_desc
- 步骤3：普通字段id @t2.id
- 步骤4：特殊字段lastRunResult正序 @t1.lastRunResult_asc
- 步骤5：空字符串输入 @t2.

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$testtaskTest = new testtaskModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($testtaskTest->addPrefixToOrderByTest('assignedTo')) && p() && e('t1.assignedTo'); // 步骤1：特殊字段assignedTo
r($testtaskTest->addPrefixToOrderByTest('status_desc')) && p() && e('t1.status_desc'); // 步骤2：特殊字段status倒序
r($testtaskTest->addPrefixToOrderByTest('id')) && p() && e('t2.id'); // 步骤3：普通字段id
r($testtaskTest->addPrefixToOrderByTest('lastRunResult_asc')) && p() && e('t1.lastRunResult_asc'); // 步骤4：特殊字段lastRunResult正序
r($testtaskTest->addPrefixToOrderByTest('')) && p() && e('t2.'); // 步骤5：空字符串输入