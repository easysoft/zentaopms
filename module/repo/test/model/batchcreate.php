#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->batchCreate();
timeout=0
cid=18029

- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1
- 执行repo模块的batchCreateTest方法，参数是array  @1

*/

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

zenData('ops_repo')->gen(0);
zenData('action')->gen(0);

$repo1 = (object)array('space' => 1, 'serviceProject' => 1, 'product' => 1, 'name' => 'import-repo-1', 'gitUID' => 'batch-create-gituid-1', 'encoding' => 'utf-8');
$repo2 = (object)array('space' => 1, 'serviceProject' => 2, 'product' => 2, 'name' => 'import-repo-2', 'gitUID' => 'batch-create-gituid-2', 'encoding' => 'utf-8');
$repo3 = (object)array('space' => 2, 'serviceProject' => 3, 'product' => 1, 'name' => 'import-repo-3', 'gitUID' => 'batch-create-gituid-3', 'encoding' => 'utf-8');
$repo4 = (object)array('space' => 2, 'serviceProject' => 4, 'product' => 2, 'name' => 'import-repo-4', 'gitUID' => 'batch-create-gituid-4', 'encoding' => 'utf-8');
$repo5 = (object)array('space' => 3, 'serviceProject' => 5, 'product' => 3, 'name' => 'import-repo-5', 'gitUID' => 'batch-create-gituid-5', 'encoding' => 'utf-8');

$repo = new repoModelTest();
baseRouter::$loadedTargets['model'][$repo->instance->appName]['instance'] = new stdclass();

r($repo->batchCreateTest(array($repo1), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo2), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo3), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo4), 1, 'Git')) && p() && e('1');
r($repo->batchCreateTest(array($repo5), 1, 'Git')) && p() && e('1');
