#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumIssueBlock();
timeout=0
cid=15287

- 步骤1:测试正常情况下传入合法type参数和count参数
 - 属性type @active
 - 属性count @5
- 步骤2:测试type参数包含特殊字符时验证失败属性hasValidation @0
- 步骤3:测试count为0时的处理属性count @0
- 步骤4:测试projectID为0时的处理
 - 属性type @resolved
 - 属性projectID @0
- 步骤5:测试不同projectID和count参数
 - 属性type @active
 - 属性count @8
- 步骤6:测试不同的type类型参数
 - 属性type @closed
 - 属性count @10

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$blockTest = new blockTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($blockTest->printScrumIssueBlockTest('active', 1, 5, 'id_desc', 'html')) && p('type,count') && e('active,5'); // 步骤1:测试正常情况下传入合法type参数和count参数
r($blockTest->printScrumIssueBlockTest('invalid-type', 1, 5, 'id_desc', 'html')) && p('hasValidation') && e('0'); // 步骤2:测试type参数包含特殊字符时验证失败
r($blockTest->printScrumIssueBlockTest('active', 1, 0, 'id_desc', 'html')) && p('count') && e('0'); // 步骤3:测试count为0时的处理
r($blockTest->printScrumIssueBlockTest('resolved', 0, 5, 'id_desc', 'html')) && p('type,projectID') && e('resolved,0'); // 步骤4:测试projectID为0时的处理
r($blockTest->printScrumIssueBlockTest('active', 2, 8, 'id_desc', 'html')) && p('type,count') && e('active,8'); // 步骤5:测试不同projectID和count参数
r($blockTest->printScrumIssueBlockTest('closed', 1, 10, 'id_desc', 'html')) && p('type,count') && e('closed,10'); // 步骤6:测试不同的type类型参数