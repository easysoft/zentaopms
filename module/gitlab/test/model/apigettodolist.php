#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetTodoList();
timeout=0
cid=1

- 通过gitlabID,projectID,超级管理员获取GitLab待办列表 @1
- 通过gitlabID,projectID,超级管理员获取GitLab待办数量 @1
- 当前项目没有待办时,获取GitLab待办列表 @0
- 当前项目有待办时,指定用户获取GitLab待办列表 @0
- 当gitlabID存在,projectID不存在时,获取GitLab待办列表 @return empty
- 当gitlabID,projectID都为0时,获取GitLab待办列表 @return empty

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 3;
$sudo      = 1;
$result    = $gitlab->apiGetTodoList($gitlabID, $projectID, $sudo);
r(isset(array_shift($result)->author)) && p() && e('1'); //通过gitlabID,projectID,超级管理员获取GitLab待办列表
r(count($result) > 0)                  && p() && e('1'); //通过gitlabID,projectID,超级管理员获取GitLab待办数量

$gitlabID  = 1;
$projectID = 2;
r(count($gitlab->apiGetTodoList($gitlabID, $projectID, $sudo))) && p() && e('0'); //当前项目没有待办时,获取GitLab待办列表

$projectID = 3;
$sudo = 4;
r(count($gitlab->apiGetTodoList($gitlabID, $projectID, $sudo))) && p() && e('0'); //当前项目有待办时,指定用户获取GitLab待办列表

$gitlabID  = 1;
$projectID = 0;
$result    = $gitlab->apiGetTodoList($gitlabID, $projectID, $sudo);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID存在,projectID不存在时,获取GitLab待办列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetTodoList($gitlabID, $projectID, $sudo);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取GitLab待办列表
