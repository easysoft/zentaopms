#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::unLinkProductsByProject();
cid=1
pid=1

*/

global $tester;
$tester->app->loadConfig('execution');

$project    = new Project();
$oldProject = new stdclass();
$data       = new stdclass();

r($project->stageProduct(1, $data, $oldProject)) && p('') && e(1); // 解除关联部分关联的产品信息。
r($project->stageProduct(2, $data, $oldProject)) && p('') && e(1); // 解除关联部分关联的产品信息。
