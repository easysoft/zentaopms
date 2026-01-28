#!/usr/bin/env php
<?php

/**

title=测试 repoModel::saveState();
timeout=0
cid=18101

- 步骤1：正常设置有效的代码库ID @2
- 步骤2：设置无效的代码库ID @1
- 步骤3：不传入代码库ID且session中无repoID @1
- 步骤4：在project tab下设置代码库ID @1
- 步骤5：测试边界值repoID为0的情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repo')->loadYaml('repo')->gen(4);

// 准备项目和产品关联数据
$projectProduct = zenData('projectproduct');
$projectProduct->project->range('11-14');
$projectProduct->product->range('1-4');
$projectProduct->gen(4);

su('admin');

$repo = new repoModelTest();

r($repo->saveStateTest(2)) && p() && e('2'); // 步骤1：正常设置有效的代码库ID
r($repo->saveStateTest(10001)) && p() && e('1'); // 步骤2：设置无效的代码库ID
r($repo->saveStateTest()) && p() && e('1'); // 步骤3：不传入代码库ID且session中无repoID
$repo->objectModel->app->tab = 'project';
r($repo->saveStateTest(2, 11)) && p() && e('1'); // 步骤4：在project tab下设置代码库ID
r($repo->saveStateTest(0)) && p() && e('1'); // 步骤5：测试边界值repoID为0的情况