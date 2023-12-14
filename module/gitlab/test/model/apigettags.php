#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetTags();
timeout=0
cid=1

- 通过gitlabID,projectID,获取GitLab标签列表 @1
- 通过gitlabID,projectID,获取GitLab标签数量 @1
- 当前项目没有标签时,获取GitLab标签列表 @0
- 通过gitlabID,projectID,获取GitLab标签列表 @return empty
- 当gitlabID,projectID都为0时,获取GitLab标签列表 @return empty
- 通过gitlabID,projectID,按标签名升序获取GitLab标签列表第1条的name属性 @tag1
- 通过gitlabID,projectID,搜索字符'zentaopms_15.2_20210720'获取GitLab标签列表第0条的name属性 @keyword_tag

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$result    = $gitlab->apiGetTags($gitlabID, $projectID);
r(isset($result[0]->name)) && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签列表
r(count($result) > 0)      && p() && e('1'); //通过gitlabID,projectID,获取GitLab标签数量

$gitlabID  = 1;
$projectID = 1;
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
$projectID = 2;
$orderBy   = 'name_asc';
$result    = $gitlab->apiGetTags($gitlabID, $projectID, $orderBy);
r($result) && p('1:name') && e('tag1'); //通过gitlabID,projectID,按标签名升序获取GitLab标签列表

$keyword = 'keyword';
$result  = $gitlab->apiGetTags($gitlabID, $projectID, $orderBy, $keyword);
r($result) && p('0:name') && e('keyword_tag'); //通过gitlabID,projectID,搜索字符'zentaopms_15.2_20210720'获取GitLab标签列表