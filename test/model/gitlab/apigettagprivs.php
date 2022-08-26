#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetTagPrivs();
cid=1
pid=1

通过gitlabID,projectID,获取GitLab标签保护列表 >> 1
通过gitlabID,projectID,获取GitLab标签保护数量 >> 1
当前项目没有标签保护时,获取GitLab标签保护列表 >> 0
通过gitlabID,projectID,获取GitLab标签保护列表 >> return empty
当gitlabID,projectID都为0时,获取GitLab标签保护列表 >> return empty

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 1552;
$result    = $gitlab->apiGetTagPrivs($gitlabID, $projectID);
r(isset(array_shift($result)->name)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签保护列表
r(count($result) > 0)                && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签保护数量

$gitlabID  = 1;
$projectID = 1570;
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