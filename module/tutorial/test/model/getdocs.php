#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDocs();
timeout=0
cid=19426

- 步骤1：验证文档ID第1条的id属性 @1
- 步骤2：验证文档标题第1条的title属性 @Test Doc
- 步骤3：验证文档类型第1条的type属性 @text
- 步骤4：验证文档状态第1条的status属性 @normal
- 步骤5：验证文档库ID第1条的lib属性 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialModelTest();

// 4. 测试步骤（必须包含至少5个测试步骤）
r($tutorialTest->getDocsTest()) && p('1:id') && e('1'); // 步骤1：验证文档ID
r($tutorialTest->getDocsTest()) && p('1:title') && e('Test Doc'); // 步骤2：验证文档标题
r($tutorialTest->getDocsTest()) && p('1:type') && e('text'); // 步骤3：验证文档类型
r($tutorialTest->getDocsTest()) && p('1:status') && e('normal'); // 步骤4：验证文档状态
r($tutorialTest->getDocsTest()) && p('1:lib') && e('2'); // 步骤5：验证文档库ID