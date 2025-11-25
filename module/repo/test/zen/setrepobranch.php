#!/usr/bin/env php
<?php

/**

title=测试 repoZen::setRepoBranch();
timeout=0
cid=18154

- 步骤1：设置普通分支名称属性cookieSet @1
- 步骤2：设置空字符串分支名称属性cookieSet @1
- 步骤3：设置包含特殊字符的分支名称属性cookieSet @1
- 步骤4：设置较长的分支名称属性cookieSet @1
- 步骤5：重复设置相同的分支名称属性cookieValue @master

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($repoTest->setRepoBranchTest('master')) && p('cookieSet') && e('1'); // 步骤1：设置普通分支名称
r($repoTest->setRepoBranchTest('')) && p('cookieSet') && e('1'); // 步骤2：设置空字符串分支名称
r($repoTest->setRepoBranchTest('feature/test-branch_123')) && p('cookieSet') && e('1'); // 步骤3：设置包含特殊字符的分支名称
r($repoTest->setRepoBranchTest('very-long-branch-name-with-many-characters-for-testing-purposes')) && p('cookieSet') && e('1'); // 步骤4：设置较长的分支名称
r($repoTest->setRepoBranchTest('master')) && p('cookieValue') && e('master'); // 步骤5：重复设置相同的分支名称