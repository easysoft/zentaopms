#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetBranchPrivs();
timeout=0
cid=1

- 获取指定项目下受保护分支列表第master条的name属性 @master
- 获取指定项目下受保护分支数量 @1
- 获取无保护分支的项目下受保护分支数量 @0
- 通过不存在projectID,获取受保护分支列表属性message @404 Project Not Found
- 当gitlabID,projectID都为0时,获取受保护分支列表 @return empty

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$keyword   = '';
$orderBy   = 'name_desc';
$result = $gitlab->apiGetBranchPrivs($gitlabID, $projectID, $keyword, $orderBy);
r($result)            && p('master:name') && e('master'); //获取指定项目下受保护分支列表
r(count($result) > 0) && p()              && e('1');      //获取指定项目下受保护分支数量

$projectID = 1;
r(count($gitlab->apiGetBranchPrivs($gitlabID, $projectID))) && p() && e('0'); //获取无保护分支的项目下受保护分支数量

$projectID = 0;
r($gitlab->apiGetBranchPrivs($gitlabID, $projectID)) && p('message') && e('404 Project Not Found'); //通过不存在projectID,获取受保护分支列表

$gitlabID  = 0;
$projectID = 0;
$result    = $gitlab->apiGetBranchPrivs($gitlabID, $projectID);
if(empty($result)) $result = 'return empty';
r($result) && p() && e('return empty'); //当gitlabID,projectID都为0时,获取受保护分支列表