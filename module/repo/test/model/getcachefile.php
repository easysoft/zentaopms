#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCacheFile();
timeout=0
cid=18050

- 步骤1：正常情况 @1
- 步骤2：空路径 @1
- 步骤3：空版本号 @1
- 步骤4：边界值 @1
- 步骤5：特殊字符 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->SCM->range('Git{3},Subversion{2}');
$table->path->range('/path/to/repo1,/path/to/repo2,/path/to/repo3,/path/to/repo4,/path/to/repo5');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 执行测试步骤
r($repoTest->getCacheFileTest(1, 'src/main.php', 'v1.0.0')) && p() && e('1'); // 步骤1：正常情况
r($repoTest->getCacheFileTest(2, '', 'HEAD')) && p() && e('1'); // 步骤2：空路径
r($repoTest->getCacheFileTest(3, 'docs/readme.txt', '')) && p() && e('1'); // 步骤3：空版本号
r($repoTest->getCacheFileTest(999, 'test/file.js', 'master')) && p() && e('1'); // 步骤4：边界值
r($repoTest->getCacheFileTest(5, 'path/with spaces/file@#$.txt', 'branch-#123')) && p() && e('1'); // 步骤5：特殊字符