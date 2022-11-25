#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->getGitlabMenu();
cid=1
pid=1

打印gitlab用户的菜单     >> <div
打印gitlab分组的菜单     >> <div
打印gitlab项目的菜单     >> <div
打印gitlab默认的菜单     >> <div
打印gitlabID不存在的菜单 >> <div

*/

$gitlab = new gitlabTest();

$gitlabID = 1;
r($gitlab->getGitlabMenuTest($gitlabID, 'user'))    && p() && e('<div');     // 打印gitlab用户的菜单
r($gitlab->getGitlabMenuTest($gitlabID, 'group'))   && p() && e('<div');     // 打印gitlab分组的菜单
r($gitlab->getGitlabMenuTest($gitlabID, 'project')) && p() && e('<div');     // 打印gitlab分组的菜单
r($gitlab->getGitlabMenuTest($gitlabID))            && p() && e('<div');     // 打印gitlab默认的菜单

$gitlabID = 111;
r($gitlab->getGitlabMenuTest($gitlabID)) && p() && e('<div');     // 使用不存在的ID
