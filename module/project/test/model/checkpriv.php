#!/usr/bin/env php
<?php

/**

title=测试 projectModel::checkPriv;
cid=0

- 查看普通用户是否有开放项目的权限 @1
- 查看项目创建用户是否有私有项目的权限 @1
- 查看普通用户是否有项目集内公开项目的权限 @0
- 查看管理用户是否有开放项目的权限 @1
- 查看管理用户是否有私有项目的权限 @1
- 查看管理用户是否有项目集内公开项目的权限 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('group')->gen(0);
zdTable('usergroup')->gen(0);
zdTable('acl')->gen(0);
zdTable('userview')->gen(0);
zdTable('project')->config('project')->gen(8);
zdTable('user')->config('user')->gen(2);

global $tester,$app;
$tester->loadModel('project');

su('user1');
$tester->project->app->user->admin = false;
$tester->project->app->user->view->projects = '1,2';
r($tester->project->checkPriv(1)) && p() && e('1'); //查看普通用户是否有开放项目的权限
r($tester->project->checkPriv(2)) && p() && e('1'); //查看项目创建用户是否有私有项目的权限
r($tester->project->checkPriv(3)) && p() && e('0'); //查看普通用户是否有项目集内公开项目的权限

su('admin');
$tester->project->app->user->admin = true;
r($tester->project->checkPriv(1)) && p() && e('1'); //查看管理用户是否有开放项目的权限
r($tester->project->checkPriv(2)) && p() && e('1'); //查看管理用户是否有私有项目的权限
r($tester->project->checkPriv(3)) && p() && e('1'); //查看管理用户是否有项目集内公开项目的权限
