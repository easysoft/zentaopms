#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteLabel();
timeout=0
cid=1

- 使用空的projectID删除gitlab群组 @0
- 使用空的label名称删除gitlab群组 @0
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,label名称正确删除GitLab label @1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;

/* Create label. */
$label = new stdclass();
$label->name        = 'unitLabelTest';
$label->color       = '#0033CC';
$label->description = 'unittest description';
$gitlab->apiCreateLabel($gitlabID, $projectID, $label);

r($gitlab->apiDeleteLabel($gitlabID, 0, $label->name))    && p() && e('0'); //使用空的projectID删除gitlab群组
r($gitlab->apiDeleteLabel($gitlabID, $projectID, '')) && p() && e('0'); //使用空的label名称删除gitlab群组
r($gitlab->apiDeleteLabel(0, $projectID, $label->name))   && p() && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteLabel($gitlabID, $projectID, $label->name);
if(is_null($result)) $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,label名称正确删除GitLab label