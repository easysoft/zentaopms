#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->createZentaoObjectLabel();
timeout=0
cid=1

- 使用空的gitlabID,projectID,objectID创建GitLab label @0
- 使用空的gitlabID、projectID,正确的object信息创建GitLab label @0
- 使用正确的gitlabID、object信息，错误的projectID创建label属性message @404 Project Not Found
- 通过gitlabID,projectID,object信息正确创建GitLab label @1

*/

zdTable('relation')->config('relation')->gen(4);

$gitlab = new gitlabTest();

$gitlabID   = 1;
$projectID  =  2;
$objectType = 'task';
$objectID   = 18;

r($gitlab->createZentaoObjectLabelTest(0, 0, 'task', 0))    && p()          && e('0'); //使用空的gitlabID,projectID,objectID创建GitLab label
r($gitlab->createZentaoObjectLabelTest(0, 0, $objectType, $objectID))         && p()          && e('0'); //使用空的gitlabID、projectID,正确的object信息创建GitLab label
r($gitlab->createZentaoObjectLabelTest($gitlabID, 0, $objectType, $objectID)) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、object信息，错误的projectID创建label

$result = $gitlab->createZentaoObjectLabelTest($gitlabID, $projectID, $objectType, $objectID);
if(isset($result->name) && $result->name == 'zentao_task/18') $result = true;
if(isset($result->message) && $result->message == 'Label already exists') $result = true;
r($result) && p() && e('1'); //通过gitlabID,projectID,object信息正确创建GitLab label