#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetBranches();
cid=1
pid=1

通过gitlabID,projectID,获取GitLab分支列表 >> branch1
通过gitlabID,projectID,获取GitLab分支数量 >> 3
当前项目没有分支时,获取GitLab分支列表 >> 0
当gitlabID存在,projectID不存在时,获取GitLab分支列表 >> 404 Project Not Found
当gitlabID,projectID都为0时,获取GitLab分支列表 >> return empty

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 1552;
$result    = $gitlab->apiGetBranches($gitlabID, $projectID);
r($result)        && p('0:name') && e('branch1'); //通过gitlabID,projectID,获取GitLab分支列表
r(count($result)) && p()         && e(3);         //通过gitlabID,projectID,获取GitLab分支数量

$gitlabID  = 1;
$projectID = 1570;
r(count($gitlab->apiGetBranches($gitlabID, $projectID))) && p() && e(0); //当前项目没有分支时,获取GitLab分支列表

$gitlabID  = 1;
$projectID = 0;
r($gitlab->apiGetBranches($gitlabID, $projectID)) && p('message') && e('404 Project Not Found'); //通过gitlabID,projectID,获取GitLab分支列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetBranches($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLab分支列表
