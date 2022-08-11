#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetTags();
cid=1
pid=1

通过gitlabID,projectID,获取GitLab标签列表 >> 1
通过gitlabID,projectID,获取GitLab标签数量 >> 1
当前项目没有标签时,获取GitLab标签列表 >> 0
通过gitlabID,projectID,获取GitLab标签列表 >> return empty
当gitlabID,projectID都为0时,获取GitLab标签列表 >> return empty
通过gitlabID,projectID,按标签名升序获取GitLab标签列表 >> with_cicredentials
通过gitlabID,projectID,搜索字符'zentaopms_15.2_20210720'获取GitLab标签列表 >> zentaopms_15.2_20210720
通过gitlabID,projectID,每页20条记录，分页获取第二页GitLab标签列表 >> zentaopms_2.0_stable_20110503

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 1552;
$result    = $gitlab->apiGetTags($gitlabID, $projectID);
r(isset($result[0]->name)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签列表
r(count($result) > 0)      && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签数量

$gitlabID  = 1;
$projectID = 1570;
r(count($gitlab->apiGetTags($gitlabID, $projectID))) && p() && e('0'); //当前项目没有标签时,获取GitLab标签列表

$gitlabID  = 1;
$projectID = 0;
$result    = $gitlab->apiGetTags($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p('') && e('return empty'); //通过gitlabID,projectID,获取GitLab标签列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetTags($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLab标签列表

$gitlabID  = 1;
$projectID = 964;
$orderBy   = 'name_asc';
$result    = $gitlab->apiGetTags($gitlabID, $projectID, $orderBy);
r($result) && p('0:name') && e('with_cicredentials'); //通过gitlabID,projectID,按标签名升序获取GitLab标签列表

$keyword = 'zentaopms_15.2_20210720';
$result  = $gitlab->apiGetTags($gitlabID, $projectID, $orderBy, $keyword);
r($result) && p('0:name') && e('zentaopms_15.2_20210720'); //通过gitlabID,projectID,搜索字符'zentaopms_15.2_20210720'获取GitLab标签列表

$tester->app->loadClass('pager', $static = true);
$pager  = new pager(0, 20, 2);
$result = $gitlab->apiGetTags($gitlabID, $projectID, $orderBy, '', $pager);
r($result) && p('0:name') && e('zentaopms_2.0_stable_20110503'); //通过gitlabID,projectID,每页20条记录，分页获取第二页GitLab标签列表