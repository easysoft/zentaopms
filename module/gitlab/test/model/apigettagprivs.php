#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetTagPrivs();
timeout=0
cid=1

- 通过gitlabID,projectID,获取GitLab标签保护列表 @1
- 通过gitlabID,projectID,获取GitLab标签保护数量 @1
- 当前项目没有标签保护时,获取GitLab标签保护列表 @0
- 通过gitlabID,projectID,获取GitLab标签保护列表 @return empty
- 当gitlabID,projectID都为0时,获取GitLab标签保护列表 @return empty

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$result    = $gitlab->apiGetTagPrivs($gitlabID, $projectID);
r(isset(array_shift($result)->name)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签保护列表
r(count($result) > 0)                && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签保护数量

$gitlabID  = 1;
$projectID = 1;
r(count($gitlab->apiGetTagPrivs($gitlabID, $projectID))) && p() && e('0'); //当前项目没有标签保护时,获取GitLab标签保护列表

$gitlabID  = 1;
$projectID = 0;
$result    = $gitlab->apiGetTagPrivs($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p('') && e('return empty'); //通过gitlabID,projectID,获取GitLab标签保护列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetTagPrivs($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLab标签保护列表