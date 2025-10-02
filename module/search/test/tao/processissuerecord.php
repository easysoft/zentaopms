#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processIssueRecord();
timeout=0
cid=18336

- 测试问题无lib时使用issue模块和view方法 >> URL包含m=issue
- 测试问题有lib时使用assetlib模块和issueView方法 >> URL包含m=assetlib且f=issueView
- 测试owner为空时的额外类型 >> extraType为commonIssue
- 测试owner存在时的额外类型 >> extraType为stakeholderIssue
- 测试URL包含正确的问题ID参数 >> URL包含正确的id参数

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$issueTable = zenData('issue');
$issueTable->id->range('1-10');
$issueTable->project->range('1-3');
$issueTable->title->range('问题{1-10}');
$issueTable->owner->range('admin{3},[]{7}');
$issueTable->lib->range('0{5},1-5');
$issueTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：测试issue无lib时使用issue模块和view方法
$issueWithoutLib = (object)array('objectType' => 'issue', 'objectID' => 1, 'title' => '测试问题1', 'summary' => '摘要1');
$objectListWithoutLib = array('issue' => array(1 => (object)array('project' => 1, 'owner' => 'admin', 'lib' => 0)));
$result1 = $searchTest->processIssueRecordTest($issueWithoutLib, $objectListWithoutLib);
r(strpos($result1->url, 'm=issue') !== false) && p() && e('1');

// 步骤2：测试issue有lib时使用assetlib模块和issueView方法
$issueWithLib = (object)array('objectType' => 'issue', 'objectID' => 6, 'title' => '测试问题6', 'summary' => '摘要6');
$objectListWithLib = array('issue' => array(6 => (object)array('project' => 2, 'owner' => 'admin', 'lib' => 1)));
$result2 = $searchTest->processIssueRecordTest($issueWithLib, $objectListWithLib);
r(strpos($result2->url, 'm=assetlib') !== false && strpos($result2->url, 'f=issueView') !== false) && p() && e('1');

// 步骤3：测试owner为空时的额外类型
$issueWithoutOwner = (object)array('objectType' => 'issue', 'objectID' => 4, 'title' => '测试问题4', 'summary' => '摘要4');
$objectListWithoutOwner = array('issue' => array(4 => (object)array('project' => 1, 'owner' => '', 'lib' => 0)));
$result3 = $searchTest->processIssueRecordTest($issueWithoutOwner, $objectListWithoutOwner);
r($result3->extraType) && p() && e('commonIssue');

// 步骤4：测试owner存在时的额外类型
$issueWithOwner = (object)array('objectType' => 'issue', 'objectID' => 2, 'title' => '测试问题2', 'summary' => '摘要2');
$objectListWithOwner = array('issue' => array(2 => (object)array('project' => 1, 'owner' => 'admin', 'lib' => 0)));
$result4 = $searchTest->processIssueRecordTest($issueWithOwner, $objectListWithOwner);
r($result4->extraType) && p() && e('stakeholderIssue');

// 步骤5：测试URL包含正确的问题ID参数
$issueForProject = (object)array('objectType' => 'issue', 'objectID' => 3, 'title' => '测试问题3', 'summary' => '摘要3');
$objectListForProject = array('issue' => array(3 => (object)array('project' => 1, 'owner' => 'admin', 'lib' => 0)));
$result5 = $searchTest->processIssueRecordTest($issueForProject, $objectListForProject);
r(strpos($result5->url, 'id=3') !== false) && p() && e('1');
