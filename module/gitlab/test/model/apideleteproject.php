#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**
title=测试 gitlabModel::apiDeleteProject();
timeout=0
cid=16591

- 使用空的projectID删除项目 @0
- 使用错误gitlabID删除项目 @0
- 使用空的GitlabID和空的projectID删除项目 @0
- 使用错误projectID删除项目 @0
- 通过gitlabID,项目id正确删除项目属性message @202 Accepted
*/

zenData('pipeline')->gen(5);

$gitlab    = new gitlabModelTest();
$gitlabID  = 1;
$projectID = 100;

r($gitlab->apiDeleteProjectTest($gitlabID, 0))          && p() && e('0'); //使用空的projectID删除项目
r($gitlab->apiDeleteProjectTest(0, $projectID))         && p() && e('0'); //使用错误gitlabID删除项目
r($gitlab->apiDeleteProjectTest(0, 0))                  && p() && e('0'); //使用空的GitlabID和空的projectID删除项目
r($gitlab->apiDeleteProjectTest(0, 99999))              && p() && e('0'); //使用错误projectID删除项目
r($gitlab->apiDeleteProjectTest($gitlabID, $projectID)) && p('message') && e('202 Accepted'); //通过gitlabID,项目id正确删除项目
