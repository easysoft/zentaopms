#!/usr/bin/env php
<?php

/**

title=测试 repoZen::locateDiffPage();
timeout=0
cid=0

- 步骤1：正常情况属性result @success
- 步骤2：不同arrange参数属性result @success
- 步骤3：分支标签标识属性result @success
- 步骤4：文件路径参数属性result @success
- 步骤5：不同参数组合属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';
include dirname(__FILE__, 2) . '/lib/repozen_locatediffpage.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('test-repo-{1-10}');
$table->SCM->range('Git,Subversion');
$table->path->range('/path/to/repo{1-10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoZenTest = new repoZenLocateDiffPageTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoZenTest->locateDiffPageTest(1, 1, 'left-right', 0, '')) && p('result') && e('success'); // 步骤1：正常情况
r($repoZenTest->locateDiffPageTest(1, 1, 'top-bottom', 0, '')) && p('result') && e('success'); // 步骤2：不同arrange参数
r($repoZenTest->locateDiffPageTest(1, 1, 'left-right', 1, '')) && p('result') && e('success'); // 步骤3：分支标签标识
r($repoZenTest->locateDiffPageTest(1, 1, 'left-right', 0, 'test/file.php')) && p('result') && e('success'); // 步骤4：文件路径参数
r($repoZenTest->locateDiffPageTest(2, 2, 'inline', 0, 'src/main.c')) && p('result') && e('success'); // 步骤5：不同参数组合