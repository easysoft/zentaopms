#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::checkUserAccess();
cid=1
pid=1

使用超级管理员判断权限 >> 1
切换普通用户，使用错误的项目ID判断权限 >> 0
切换普通用户，使用当前用户无身份的项目ID判断权限 >> 0
切换普通用户，使用当前用户是拥有者的项目ID判断权限 >> 1
切换普通用户，使用错误的项目信息判断权限是否有维护者权限 >> 0
切换普通用户，使用错误的项目信息判断权限是否有维护者权限 >> 1
切换普通用户，使用当前用户是开发者的项目ID判断权限是否有维护者权限 >> 0
切换普通用户，使用当前用户是拥有者的项目ID判断权限是否有维护者权限 >> 1

*/

$gitlab = $tester->loadModel('gitlab');

su('admin');
$gitlabID = 1;
r($gitlab->checkUserAccess($gitlabID)) && p() && e('1'); //使用超级管理员判断权限

su('user57');
$projectID = 0;
r($gitlab->checkUserAccess($gitlabID, $projectID)) && p() && e('0'); //切换普通用户，使用错误的项目ID判断权限

$projectID = 1582;
r($gitlab->checkUserAccess($gitlabID, $projectID)) && p() && e('0'); //切换普通用户，使用当前用户无身份的项目ID判断权限

$projectID = 1588;
r($gitlab->checkUserAccess($gitlabID, $projectID)) && p() && e('1'); //切换普通用户，使用当前用户是拥有者的项目ID判断权限

$project = new stdclass();
$project->param = 'empty';
r($gitlab->checkUserAccess($gitlabID, $projectID, $project)) && p() && e('0'); //切换普通用户，使用错误的项目信息判断权限是否有维护者权限

$project = $gitlab->apiGetSingleProject($gitlabID, $projectID);
r($gitlab->checkUserAccess($gitlabID, $projectID, $project)) && p() && e('1'); //切换普通用户，使用错误的项目信息判断权限是否有维护者权限

$projectID = 9;
r($gitlab->checkUserAccess($gitlabID, $projectID, null, array(), 'maintainer')) && p() && e('0'); //切换普通用户，使用当前用户是开发者的项目ID判断权限是否有维护者权限

r($gitlab->checkUserAccess($gitlabID, $projectID, $project, array(), 'maintainer')) && p() && e('1'); //切换普通用户，使用当前用户是拥有者的项目ID判断权限是否有维护者权限