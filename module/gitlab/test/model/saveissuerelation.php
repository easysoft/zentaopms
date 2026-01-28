#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试gitlabModel->saveIssueRelation();
timeout=0
cid=16666

- issue为空时获取结果。 @0
- issue正确时获取结果。
 - 属性AID @18
 - 属性AType @task
 - 属性BID @4
 - 属性BType @issue
 - 属性relation @gitlab

*/

zenData('pipeline')->gen(5);
zenData('relation')->loadYaml('relation')->gen(4);

$gitlab  = new gitlabModelTest();

$gitlabID   = 1;
$projectID  = 2;
$issueID    = 4;
$objectType = 'task';

r($gitlab->saveIssueRelationTest($objectType, $gitlabID, 0, $projectID))        && p()                             && e('0');                      // issue为空时获取结果。
r($gitlab->saveIssueRelationTest($objectType, $gitlabID, $issueID, $projectID)) && p('AID,AType,BID,BType,relation') && e('18,task,4,issue,gitlab'); // issue正确时获取结果。