#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

zenData('projectadmin')->gen(0);

zenData('project')->gen(15);
$group = zenData('group');
$group->role->range('projectAdmin');
$group->gen(15);

/**

title=测试 projectModel->create();
timeout=0
cid=1

*/

$projectClass = new project();

r($projectClass->addProjectAdminTest(1)) && p('projects', '|') && e('1');     // 测试将项目ID为1添加管理员至分组
r($projectClass->addProjectAdminTest(2)) && p('projects', '|') && e('1,2');   // 测试将项目ID为2添加管理员至分组
r($projectClass->addProjectAdminTest(3)) && p('projects', '|') && e('1,2,3'); // 测试将项目ID为3添加管理员至分组
