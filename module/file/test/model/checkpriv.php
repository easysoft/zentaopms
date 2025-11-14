#!/usr/bin/env php
<?php

/**

title=测试 fileModel->checkPriv();
timeout=0
cid=16494

- 查看超级管理员是否有下载附件1的权限 @1
- 查看超级管理员是否有下载附件2的权限 @1
- 查看超级管理员是否有下载附件3的权限 @1
- 查看超级管理员是否有下载附件4的权限 @1
- 查看超级管理员是否有下载附件6的权限 @0
- 查看没有权限用户是否有下载附件1的权限 @0
- 查看没有权限用户是否有下载附件2的权限 @0
- 查看没有权限用户是否有下载附件3的权限 @0
- 查看没有权限用户是否有下载附件4的权限 @0
- 查看没有权限用户是否有下载附件6的权限 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$file = zenData('file');
$file->objectType->range('task,story,release,doc,bug');
$file->objectID->range('1');
$file->gen(50);

$task = zenData('task');
$task->execution->range('1');
$task->gen(1);

$story = zenData('story');
$story->product->range('1');
$story->gen(1);

$release = zenData('release');
$release->product->range('1');
$release->project->range('0');
$release->gen(1);

zenData('project')->gen(0);
zenData('product')->gen(0);
zenData('bug')->gen(0);
zenData('doc')->gen(1);
zenData('user')->gen(5);
zenData('userview')->gen(0);
zenData('usergroup')->gen(0);

su('admin');

global $tester, $app;
$tester->loadModel('file');
$tester->file->app->user->admin = true;
$file1 = $tester->file->getByID(1);
$file2 = $tester->file->getByID(12);
$file3 = $tester->file->getByID(23);
$file4 = $tester->file->getByID(34);
$file5 = $tester->file->getByID(45);

r($tester->file->checkPriv($file1)) && p() && e(1); // 查看超级管理员是否有下载附件1的权限
r($tester->file->checkPriv($file2)) && p() && e(1); // 查看超级管理员是否有下载附件2的权限
r($tester->file->checkPriv($file3)) && p() && e(1); // 查看超级管理员是否有下载附件3的权限
r($tester->file->checkPriv($file4)) && p() && e(1); // 查看超级管理员是否有下载附件4的权限
r($tester->file->checkPriv($file5)) && p() && e(0); // 查看超级管理员是否有下载附件6的权限

su('user1');
r($tester->file->checkPriv($file1)) && p() && e(0); // 查看没有权限用户是否有下载附件1的权限
r($tester->file->checkPriv($file2)) && p() && e(0); // 查看没有权限用户是否有下载附件2的权限
r($tester->file->checkPriv($file3)) && p() && e(0); // 查看没有权限用户是否有下载附件3的权限
r($tester->file->checkPriv($file4)) && p() && e(0); // 查看没有权限用户是否有下载附件4的权限
r($tester->file->checkPriv($file5)) && p() && e(0); // 查看没有权限用户是否有下载附件6的权限