#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 gitlabModel::isClickable();
timeout=0
cid=1

- 计算isDeveloper为true是否能进行浏览分支操作 @1
- 计算isDeveloper为false是否能进行浏览分支操作 @0
- 计算isDeveloper为true是否能进行浏览标签操作 @1
- 计算isDeveloper为false是否能进行浏览标签操作 @0
- 计算hasRepo为true是否能进行项目成员管理操作 @1
- 计算hasRepo为false是否能进行项目成员管理操作 @0
- 计算hasRepo为true是否能进行webhook创建操作 @1
- 计算hasRepo为false是否能进行webhook创建操作 @0
- 计算defaultBranch为true是否能进行分支保护管理操作 @1
- 计算defaultBranch为false是否能进行分支保护管理操作 @0
- 计算defaultBranch为true是否能进行标签保护管理操作 @1
- 计算defaultBranch为false是否能进行标签保护管理操作 @0
- 计算defaultBranch为true是否能进行项目编辑操作 @1
- 计算defaultBranch为false是否能进行项目编辑操作 @0
- 计算defaultBranch为true是否能进行删除项目操作 @1
- 计算defaultBranch为false是否能进行删除项目操作 @0
- 计算isAdmin为true是否能进行编辑群组操作 @1
- 计算isAdmin为false是否能进行编辑群组操作 @0
- 计算isAdmin为true是否能进行删除群组操作 @1
- 计算isAdmin为false是否能进行删除群组操作 @0
- 计算isAdmin为true是否能进行编辑用户操作 @1
- 计算isAdmin为false是否能进行编辑用户操作 @0
- 计算isAdmin为true是否能进行删除用户操作 @1
- 计算isAdmin为false是否能进行删除用户操作 @0
- 计算protected为true是否能进行删除tag操作 @0
- 计算protected为false是否能进行删除tag操作 @1

*/

$gitlabModel = $tester->loadModel('gitlab');

$gitlab1 = new stdclass();
$gitlab1->isDeveloper = true;

$gitlab2 = new stdclass();
$gitlab2->isDeveloper = false;

$gitlab3 = new stdclass();
$gitlab3->hasRepo = true;

$gitlab4 = new stdclass();
$gitlab4->hasRepo = false;

$gitlab5 = new stdclass();
$gitlab5->defaultBranch = true;

$gitlab6 = new stdclass();
$gitlab6->defaultBranch = false;

$gitlab7 = new stdclass();
$gitlab7->isAdmin = true;

$gitlab8 = new stdclass();
$gitlab8->isAdmin = false;

$gitlab9 = new stdclass();
$gitlab9->protected = true;

$gitlab10 = new stdclass();
$gitlab10->protected = false;

r($gitlabModel->isClickable($gitlab1, 'browseBranch')) && p() && e('1'); //计算isDeveloper为true是否能进行浏览分支操作
r($gitlabModel->isClickable($gitlab2, 'browseBranch')) && p() && e('0'); //计算isDeveloper为false是否能进行浏览分支操作
r($gitlabModel->isClickable($gitlab1, 'browseTag'))    && p() && e('1'); //计算isDeveloper为true是否能进行浏览标签操作
r($gitlabModel->isClickable($gitlab2, 'browseTag'))    && p() && e('0'); //计算isDeveloper为false是否能进行浏览标签操作

r($gitlabModel->isClickable($gitlab3, 'manageProjectMembers')) && p() && e('1'); //计算hasRepo为true是否能进行项目成员管理操作
r($gitlabModel->isClickable($gitlab4, 'manageProjectMembers')) && p() && e('0'); //计算hasRepo为false是否能进行项目成员管理操作
r($gitlabModel->isClickable($gitlab3, 'createWebhook'))        && p() && e('1'); //计算hasRepo为true是否能进行webhook创建操作
r($gitlabModel->isClickable($gitlab4, 'createWebhook'))        && p() && e('0'); //计算hasRepo为false是否能进行webhook创建操作

r($gitlabModel->isClickable($gitlab5, 'manageBranchPriv')) && p() && e('1'); //计算defaultBranch为true是否能进行分支保护管理操作
r($gitlabModel->isClickable($gitlab6, 'manageBranchPriv')) && p() && e('0'); //计算defaultBranch为false是否能进行分支保护管理操作
r($gitlabModel->isClickable($gitlab5, 'manageTagPriv'))    && p() && e('1'); //计算defaultBranch为true是否能进行标签保护管理操作
r($gitlabModel->isClickable($gitlab6, 'manageTagPriv'))    && p() && e('0'); //计算defaultBranch为false是否能进行标签保护管理操作
r($gitlabModel->isClickable($gitlab5, 'editProject'))      && p() && e('1'); //计算defaultBranch为true是否能进行项目编辑操作
r($gitlabModel->isClickable($gitlab6, 'editProject'))      && p() && e('0'); //计算defaultBranch为false是否能进行项目编辑操作
r($gitlabModel->isClickable($gitlab5, 'deleteProject'))    && p() && e('1'); //计算defaultBranch为true是否能进行删除项目操作
r($gitlabModel->isClickable($gitlab6, 'deleteProject'))    && p() && e('0'); //计算defaultBranch为false是否能进行删除项目操作

r($gitlabModel->isClickable($gitlab7, 'editGroup'))   && p() && e('1'); //计算isAdmin为true是否能进行编辑群组操作
r($gitlabModel->isClickable($gitlab8, 'editGroup'))   && p() && e('0'); //计算isAdmin为false是否能进行编辑群组操作
r($gitlabModel->isClickable($gitlab7, 'deleteGroup')) && p() && e('1'); //计算isAdmin为true是否能进行删除群组操作
r($gitlabModel->isClickable($gitlab8, 'deleteGroup')) && p() && e('0'); //计算isAdmin为false是否能进行删除群组操作
r($gitlabModel->isClickable($gitlab7, 'editUser'))    && p() && e('1'); //计算isAdmin为true是否能进行编辑用户操作
r($gitlabModel->isClickable($gitlab8, 'editUser'))    && p() && e('0'); //计算isAdmin为false是否能进行编辑用户操作
r($gitlabModel->isClickable($gitlab7, 'deleteUser'))  && p() && e('1'); //计算isAdmin为true是否能进行删除用户操作
r($gitlabModel->isClickable($gitlab8, 'deleteUser'))  && p() && e('0'); //计算isAdmin为false是否能进行删除用户操作

r($gitlabModel->isClickable($gitlab9, 'deleteTag'))  && p() && e('0'); //计算protected为true是否能进行删除tag操作
r($gitlabModel->isClickable($gitlab10, 'deleteTag')) && p() && e('1'); //计算protected为false是否能进行删除tag操作
