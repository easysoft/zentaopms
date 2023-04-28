#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

zdTable('project')->gen(5);
zdTable('projectadmin')->gen(0);

/**

title=测试 projectModel->create();
timeout=0
cid=1

- 执行projectClass模块的addProjectAdminTest方法，参数是1属性projects @1

- 执行projectClass模块的addProjectAdminTest方法，参数是2属性projects @1,2

- 执行projectClass模块的addProjectAdminTest方法，参数是3属性projects @1,2,3



*/

$projectClass = new project();

r($projectClass->addProjectAdminTest(1)) && p('projects', '|') && e('1');
r($projectClass->addProjectAdminTest(2)) && p('projects', '|') && e('1,2');
r($projectClass->addProjectAdminTest(3)) && p('projects', '|') && e('1,2,3');
