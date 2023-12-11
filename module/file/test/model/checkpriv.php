#!/usr/bin/env php
<?php

/**

title=测试 fileModel->checkPriv();
cid=0

- 查看超级管理员是否有下载附件1的权限 @1
- 查看超级管理员是否有下载附件2的权限 @1
- 查看超级管理员是否有下载附件3的权限 @1
- 查看超级管理员是否有下载附件4的权限 @1
- 查看超级管理员是否有下载附件6的权限 @1
- 查看没有权限用户是否有下载附件1的权限 @0
- 查看没有权限用户是否有下载附件2的权限 @0
- 查看没有权限用户是否有下载附件3的权限 @0
- 查看没有权限用户是否有下载附件4的权限 @1
- 查看没有权限用户是否有下载附件6的权限 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$file = zdTable('file');
$file->objectType->range('task,story,release,doc,traincourse,bug');
$file->objectID->range('1');
$file->gen(50);

$task = zdTable('task');
$task->execution->range('1');
$task->gen(1);

$story = zdTable('story');
$story->product->range('1');
$story->gen(1);

$release = zdTable('release');
$release->product->range('1');
$release->project->range('0');
$release->gen(1);

$doc = zdTable('doc');
$doc->gen(1);

global $tester;
$tester->loadModel('file');
$file1 = $tester->file->getByID(1);
$file2 = $tester->file->getByID(2);
$file3 = $tester->file->getByID(3);
$file4 = $tester->file->getByID(4);
$file5 = $tester->file->getByID(5);

$tester->file->app->user->admin = true;

r($tester->file->checkPriv($file1)) && p() && e(1); // 查看超级管理员是否有下载附件1的权限
r($tester->file->checkPriv($file2)) && p() && e(1); // 查看超级管理员是否有下载附件2的权限
r($tester->file->checkPriv($file3)) && p() && e(1); // 查看超级管理员是否有下载附件3的权限
r($tester->file->checkPriv($file4)) && p() && e(1); // 查看超级管理员是否有下载附件4的权限
r($tester->file->checkPriv($file5)) && p() && e(1); // 查看超级管理员是否有下载附件6的权限

$tester->file->app->user->admin = false;
$tester->file->app->user->view->products = '';
$tester->file->app->user->view->projects = '';
$tester->file->app->user->view->sprints  = '';

r($tester->file->checkPriv($file1)) && p() && e(0); // 查看没有权限用户是否有下载附件1的权限
r($tester->file->checkPriv($file2)) && p() && e(0); // 查看没有权限用户是否有下载附件2的权限
r($tester->file->checkPriv($file3)) && p() && e(0); // 查看没有权限用户是否有下载附件3的权限
r($tester->file->checkPriv($file4)) && p() && e(1); // 查看没有权限用户是否有下载附件4的权限
r($tester->file->checkPriv($file5)) && p() && e(1); // 查看没有权限用户是否有下载附件6的权限
