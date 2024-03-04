#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateLabel();
timeout=0
cid=1

- 使用空的gitlabID,projectID,label对象创建GitLab label @0
- 使用空的gitlabID、projectID,正确的label对象创建GitLab label @0
- 使用正确的gitlabID、label信息，错误的projectID创建label属性message @404 Project Not Found
- 通过gitlabID,projectID,label对象正确创建GitLab label @1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$emptyLabel = new stdclass();

$label = new stdclass();
$label->name        = 'unitLabelTest';
$label->color       = '#0033CC';
$label->description = 'unittest description';

r($gitlab->apiCreateLabel(0, 0, $emptyLabel))    && p()          && e('0'); //使用空的gitlabID,projectID,label对象创建GitLab label
r($gitlab->apiCreateLabel(0, 0, $label))         && p()          && e('0'); //使用空的gitlabID、projectID,正确的label对象创建GitLab label
r($gitlab->apiCreateLabel($gitlabID, 0, $label)) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、label信息，错误的projectID创建label

$result = $gitlab->apiCreateLabel($gitlabID, $projectID, $label);
if(isset($result->name) && $result->name == 'unitLabelTest') $result = true;
if(isset($result->message) && $result->message == 'Label already exists') $result = true;
r($result) && p() && e('1'); //通过gitlabID,projectID,label对象正确创建GitLab label