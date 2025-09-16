#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildTaskSearchForm();
timeout=0
cid=0

- 步骤1：正常情况属性result @success
- 步骤2：模块数据属性moduleCount @3
- 步骤3：执行列表属性executionCount @3
- 步骤4：空模块属性moduleCount @0
- 步骤5：空执行列表属性executionCount @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_buildtasksearchform.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-5');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->SCM->range('Git{3},Gitlab{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildTaskSearchFormTest(1, 'main', 'bySearch', 1, array('module1' => '模块1', 'module2' => '模块2'), array('exec1' => '执行1', 'exec2' => '执行2'))) && p('result') && e('success'); // 步骤1：正常情况
r($repoTest->buildTaskSearchFormTest(2, 'develop', 'bySearch', 2, array('module1' => '模块1', 'module2' => '模块2', 'module3' => '模块3'), array('exec1' => '执行1'))) && p('moduleCount') && e('3'); // 步骤2：模块数据
r($repoTest->buildTaskSearchFormTest(3, 'feature', 'bySearch', 3, array('module1' => '模块1'), array('exec1' => '执行1', 'exec2' => '执行2', 'exec3' => '执行3'))) && p('executionCount') && e('3'); // 步骤3：执行列表
r($repoTest->buildTaskSearchFormTest(4, 'main', 'bySearch', 4, array(), array('exec1' => '执行1'))) && p('moduleCount') && e('0'); // 步骤4：空模块
r($repoTest->buildTaskSearchFormTest(5, 'main', 'bySearch', 5, array('module1' => '模块1'), array())) && p('executionCount') && e('0'); // 步骤5：空执行列表