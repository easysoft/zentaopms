#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->saveIssueRelation();
timeout=0
cid=1

- issue为空时获取结果。 @0
- issue正确时获取结果。
 - 属性AID @18
 - 属性execution @1

*/

zdTable('pipeline')->gen(5);
zdTable('relation')->config('relation')->gen(4);

$gitlab  = new gitlabTest();

$gitlabID   = 1;
$projectID  = 2;
$issueID    = 4;
$objectType = 'task';

r($gitlab->saveIssueRelationTest($objectType, $gitlabID, 0, $projectID))        && p() && e('0'); // issue为空时获取结果。
r($gitlab->saveIssueRelationTest($objectType, $gitlabID, $issueID, $projectID)) && p('AID,execution') && e('18,1'); // issue正确时获取结果。