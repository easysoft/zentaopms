#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::updateWhitelistByProject();
cid=1
pid=1

*/

global $tester;
$tester->app->loadConfig('execution');

$project    = new Project();
$oldProject = new stdclass();
$data       = new stdclass();

r($project->updateWhitelistByProject(1, $data, $oldProject) && p('') && e(1);         // 更新关联的白名单列表。
r($project->updateWhitelistByProject(2, $data, $oldProject) && p('') && e(1);         // 更新关联的白名单列表。
