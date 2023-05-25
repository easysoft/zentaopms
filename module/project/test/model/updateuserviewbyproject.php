#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel::updateUserViewByProject();
cid=1
pid=1

*/

global $tester;
$tester->app->loadConfig('execution');

$project    = new Project();
$oldProject = new stdclass();
$data       = new stdclass();

r($project->updateUserViewByProject(1, '')     && p('') && e(1);         // 更新项目关联的用户视图。
r($project->updateUserViewByProject(2, 'open') && p('') && e(1);         // 更新项目关联的用户视图。
