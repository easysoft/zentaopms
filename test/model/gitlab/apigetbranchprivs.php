#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetBranchPrivs();
cid=1
pid=1

获取指定项目下受保护分支列表 >> master
获取指定项目下受保护分支数量 >> 3
获取无保护分支的项目下受保护分支数量 >> 0
通过不存在projectID,获取受保护分支列表 >> 404 Project Not Found
当gitlabID,projectID都为0时,获取受保护分支列表 >> return empty

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 961;
$keyword   = '';
$orderBy   = 'name_desc';
$result = $gitlab->apiGetBranchPrivs($gitlabID, $projectID, $keyword, $orderBy);
r($result) && p('master:name') && e('master'); //获取指定项目下受保护分支列表
r(count($result)) && p() && e('3');              //获取指定项目下受保护分支数量

$projectID = 1572;
r(count($gitlab->apiGetBranchPrivs($gitlabID, $projectID))) && p() && e('0'); //获取无保护分支的项目下受保护分支数量

$projectID = 0;
r($gitlab->apiGetBranchPrivs($gitlabID, $projectID)) && p('message') && e('404 Project Not Found'); //通过不存在projectID,获取受保护分支列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetBranchPrivs($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取受保护分支列表