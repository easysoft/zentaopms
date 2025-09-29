#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoObjectList();
timeout=0
cid=0

- 步骤1：默认配置测试基本对象 >> 期望包含基础的story、task、testcase、bug字段
- 步骤2：默认配置包含epic和requirement >> 期望包含epic和requirement字段
- 步骤3：禁用ER功能后数量减少 >> 期望数量为5（去掉epic）
- 步骤4：禁用UR/SR功能后数量减少 >> 期望数量为5（去掉requirement）
- 步骤5：同时禁用两个功能后数量进一步减少 >> 期望数量为4（去掉epic和requirement）

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 3. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getZentaoObjectListTest()) && p('story,task,testcase,bug') && e('软件需求,任务,用例,Bug'); // 步骤1：默认配置测试基本对象
r($convertTest->getZentaoObjectListTest()) && p('epic,requirement') && e('业务需求,用户需求'); // 步骤2：默认配置包含epic和requirement
r($convertTest->getZentaoObjectListCountTest('noER')) && p() && e(5); // 步骤3：禁用ER功能后数量减少
r($convertTest->getZentaoObjectListCountTest('noUR')) && p() && e(5); // 步骤4：禁用UR/SR功能后数量减少
r($convertTest->getZentaoObjectListCountTest('noERAndUR')) && p() && e(4); // 步骤5：同时禁用两个功能后数量进一步减少