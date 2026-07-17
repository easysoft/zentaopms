#!/usr/bin/env php
<?php

/**

title=测试 ppmModel::getRelationByBranch();
timeout=0
cid=0

- 执行ppmModel模块的getRelationByBranchTest方法，参数是$repoInfo, 'feature/bbb', 'main', 'all', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0  @1
- 执行ppmModel模块的getRelationByBranchTest方法，参数是$repoInfo, 'feature/bbb', 'main', 'story', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0  @1
- 执行ppmModel模块的getRelationByBranchTest方法，参数是$repoInfo, 'feature/bbb', 'main', 'bug', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0  @1
- 执行ppmModel模块的getRelationByBranchTest方法，参数是$repoInfo, 'feature/bbb', 'main', 'task', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0  @1
- 执行ppmModel模块的getRelationByBranchTest方法，参数是$repoInfo, 'feature/bbb', 'main', 'all', $pager2), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->loadClass('pager', true);
$app->rawModule = 'ppm';
$app->rawMethod = 'browse';
$app->setMethodName('browse');

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
zenData('user')->gen(3);
zenData('product')->gen(1);

$repo = zenData('ops_repo')->loadYaml('ops_repo', false, 2);
$repo->id->range('42');
$repo->product->range('1');
$repo->name->range('ppm-repo-42');
$repo->defaultBranch->range('main');
$repo->gen(1);

su('admin');

$ppmModel = new ppmModelTest();
$repoInfo = (object)array('id' => 42);
$pager1   = new pager(0, 20, 1);
$pager2   = new pager(0, 20, 2);

r(strpos(json_encode($ppmModel->getRelationByBranchTest($repoInfo, 'feature/bbb', 'main', 'all', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0) && p() && e('1');
r(strpos(json_encode($ppmModel->getRelationByBranchTest($repoInfo, 'feature/bbb', 'main', 'story', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0) && p() && e('1');
r(strpos(json_encode($ppmModel->getRelationByBranchTest($repoInfo, 'feature/bbb', 'main', 'bug', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0) && p() && e('1');
r(strpos(json_encode($ppmModel->getRelationByBranchTest($repoInfo, 'feature/bbb', 'main', 'task', $pager1), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0) && p() && e('1');
r(strpos(json_encode($ppmModel->getRelationByBranchTest($repoInfo, 'feature/bbb', 'main', 'all', $pager2), JSON_UNESCAPED_UNICODE), '尝试认证失败') !== false ? 1 : 0) && p() && e('1');