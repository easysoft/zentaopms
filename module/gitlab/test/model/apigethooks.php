#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=PHP Deprecated:  substr(): Passing null to parameter
timeout=0
cid=1

- 通过gitlabID,projectID,获取GitLab hook列表 @1
- 通过gitlabID,projectID,获取GitLab hook数量 @1
- 通过指定hookID获取hook信息 @1
- 当前项目没有hook时,获取GitLab hook列表 @0
- 当gitlabID存在,projectID不存在时,获取GitLab hook列表属性message @404 Project Not Found
- 当gitlabID,projectID都为0时,获取GitLabi hook列表 @return empty

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID   = 1;
$projectID  = 2;
$result     = $gitlab->apiGetHooks($gitlabID, $projectID);
$hook       = end($result);
$hookResult = $gitlab->apiGetHooks($gitlabID, $projectID, $hook->id);
r(isset($hook->url))      && p() && e('1'); //通过gitlabID,projectID,获取GitLab hook列表
r(count($result) > 0)     && p() && e('1'); //通过gitlabID,projectID,获取GitLab hook数量
r(isset($hookResult->id)) && p() && e('1'); //通过指定hookID获取hook信息

$gitlabID  = 1;
$projectID = 1;
r(count($gitlab->apiGetHooks($gitlabID, $projectID))) && p() && e('0'); //当前项目没有hook时,获取GitLab hook列表

$gitlabID  = 1;
$result    = $gitlab->apiGetHooks($gitlabID, 0);
r($gitlab->apiGetHooks($gitlabID, 0)) && p('message') && e('404 Project Not Found'); //当gitlabID存在,projectID不存在时,获取GitLab hook列表

$result = $gitlab->apiGetHooks(0, 0);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLabi hook列表