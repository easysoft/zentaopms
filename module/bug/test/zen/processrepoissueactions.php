#!/usr/bin/env php
<?php

/**

title=测试 bugZen::processRepoIssueActions();
timeout=0
cid=15469

- 步骤1：正常仓库ID属性repoID @1
- 步骤2：边界值0属性repoID @0
- 步骤3：负数ID属性repoID @-1
- 步骤4：验证主要操作
 - 第mainActions条的0属性 @confirm
 - 第mainActions条的1属性 @assignTo
 - 第mainActions条的2属性 @resolve
 - 第mainActions条的3属性 @close
 - 第mainActions条的4属性 @activate
- 步骤5：验证后缀操作第suffixActions条的0属性 @delete

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$bugTest = new bugZenTest();

// 4. 执行测试步骤
r($bugTest->processRepoIssueActionsTest(1)) && p('repoID') && e('1'); // 步骤1：正常仓库ID
r($bugTest->processRepoIssueActionsTest(0)) && p('repoID') && e('0'); // 步骤2：边界值0
r($bugTest->processRepoIssueActionsTest(-1)) && p('repoID') && e('-1'); // 步骤3：负数ID
r($bugTest->processRepoIssueActionsTest(100)) && p('mainActions:0,1,2,3,4') && e('confirm,assignTo,resolve,close,activate'); // 步骤4：验证主要操作
r($bugTest->processRepoIssueActionsTest(200)) && p('suffixActions:0') && e('delete'); // 步骤5：验证后缀操作