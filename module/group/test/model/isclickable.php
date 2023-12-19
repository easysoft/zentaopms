#!/usr/bin/env php
<?php

/**

title=测试 groupModel->create();
timeout=0
cid=1

- 受限用户不能管理视野 @0
- 测试action的大小写 @0
- 受限用户不能维护项目管理员 @0
- 受限用户不能复制分组 @0
- 项目管理员可以维护项目管理员 @1
- 项目管理员可以复制分组 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->loadModel('group');

$limitedGroup      = (object)array('role' => 'limited');
$projectAdminGroup = (object)array('role' => 'projectAdmin');

r(groupModel::isClickable($limitedGroup, 'manageview'))              && p() && e('0'); // 受限用户不能管理视野
r(groupModel::isClickable($limitedGroup, 'manageView'))              && p() && e('0'); // 测试action的大小写
r(groupModel::isClickable($limitedGroup, 'manageprojectadmin'))      && p() && e('0'); // 受限用户不能维护项目管理员
r(groupModel::isClickable($limitedGroup, 'copy'))                    && p() && e('0'); // 受限用户不能复制分组
r(groupModel::isClickable($projectAdminGroup, 'manageprojectadmin')) && p() && e('1'); // 项目管理员可以维护项目管理员
r(groupModel::isClickable($projectAdminGroup, 'copy'))               && p() && e('1'); // 项目管理员可以复制分组