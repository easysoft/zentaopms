#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetBranches();
timeout=0
cid=1

- 通过gitlabID,projectID,获取GitLab分支列表 @1
- 通过gitlabID,projectID,获取GitLab分支数量 @1
- 当前项目没有分支时,获取GitLab分支列表 @0
- 当gitlabID存在,projectID不存在时,获取GitLab分支列表 @return empty
- 当gitlabID,projectID都为0时,获取GitLab分支列表 @return empty

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$result    = $gitlab->apiGetBranches($gitlabID, $projectID);
r(isset(array_shift($result)->name)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab分支列表
r(count($result) > 0)                && p() && e('1'); //通过gitlabID,projectID,获取GitLab分支数量

$gitlabID  = 1;
$projectID = 959;
r(count($gitlab->apiGetBranches($gitlabID, $projectID))) && p() && e('0'); //当前项目没有分支时,获取GitLab分支列表

$gitlabID  = 1;
$projectID = 0;
$result    = $gitlab->apiGetBranches($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID存在,projectID不存在时,获取GitLab分支列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetBranches($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLab分支列表