#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::stageProduct();
cid=1
pid=1

*/

global $tester;
$tester->app->loadConfig('execution');

$project = new Project();

r($project->stageProduct(1, 'product'))         && p('')             && e(1);         // 更新项目下的所有产品的阶段。
r($project->stageProduct(2, 'project'))         && p('')             && e(1);         // 更新项目下的所有产品的阶段。
