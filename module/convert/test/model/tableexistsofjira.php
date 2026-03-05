#!/usr/bin/env php
<?php

/**

title=测试 convertModel::tableExistsOfJira();
timeout=0
cid=15798

- 步骤1：检查存在的系统表user @1
- 步骤2：检查存在的表名bug @1
- 步骤3：检查存在的表名task @1
- 步骤4：检查不存在的表名 @0
- 步骤5：检查空字符串表名 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(!!$convertTest->tableExistsOfJiraTest('zentaoipd', 'zt_user')) && p() && e('1'); // 步骤1：检查存在的系统表user
r(!!$convertTest->tableExistsOfJiraTest('zentaoipd', 'zt_bug'))  && p() && e('1'); // 步骤2：检查存在的表名bug
r(!!$convertTest->tableExistsOfJiraTest('zentaoipd', 'zt_task')) && p() && e('1'); // 步骤3：检查存在的表名task
r(!!$convertTest->tableExistsOfJiraTest('zentaoipd', 'zt_nonexistent_table_12345')) && p() && e('0'); // 步骤4：检查不存在的表名
r(!!$convertTest->tableExistsOfJiraTest('zentaoipd', '')) && p() && e('1'); // 步骤5：检查空字符串表名
