#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::saveImportedIssue();
timeout=0
cid=16665

- 测试步骤1：正常保存task类型的issue第labels条的0属性 @zentao_task/18
- 测试步骤2：正常保存bug类型的issue第labels条的0属性 @zentao_bug/9
- 测试步骤3：正常保存story类型的issue第labels条的0属性 @zentao_story/5
- 测试步骤4：测试标签已存在的情况第labels条的0属性 @zentao_task/18
- 测试步骤5：测试无效的GitLab项目ID的处理 @~~
- 测试步骤6：测试空对象ID的边界情况 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('task')->gen(20);
zenData('bug')->gen(10);
zenData('story')->gen(10);
zenData('pipeline')->gen(5);
zenData('relation')->loadYaml('relation')->gen(4);

su('admin');

$gitlab = new gitlabModelTest();

$gitlabID   = 1;
$projectID  = 2;
$issueID    = 4;
$objectType = 'task';
$objectID   = 18;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p('labels:0') && e('zentao_task/18'); // 测试步骤1：正常保存task类型的issue

$issueID    = 3;
$objectType = 'bug';
$objectID   = 9;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p('labels:0') && e('zentao_bug/9'); // 测试步骤2：正常保存bug类型的issue

$issueID    = 2;
$objectType = 'story';
$objectID   = 5;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p('labels:0') && e('zentao_story/5'); // 测试步骤3：正常保存story类型的issue

$issueID    = 4;
$objectType = 'task';
$objectID   = 18;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p('labels:0') && e('zentao_task/18'); // 测试步骤4：测试标签已存在的情况

$gitlabID   = 999;
$projectID  = 999;
$issueID    = 1;
$objectType = 'task';
$objectID   = 1;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p() && e('~~'); // 测试步骤5：测试无效的GitLab项目ID的处理

$gitlabID   = 1;
$projectID  = 2;
$issueID    = 1;
$objectType = 'task';
$objectID   = 0;
r($gitlab->saveImportedIssueTest($gitlabID, $projectID, $objectType, $objectID, $issueID)) && p() && e('~~'); // 测试步骤6：测试空对象ID的边界情况