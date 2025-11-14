#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createRelease();
timeout=0
cid=15843

- 步骤1：正常创建发布版本 @1
- 步骤2：创建已归档的发布版本 @1
- 步骤3：创建等待发布的版本 @1
- 步骤4：创建带关联需求的发布版本 @1
- 步骤5：创建带关联缺陷的发布版本 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$build = zenData('build');
$build->id->range('1-5');
$build->product->range('1-3');
$build->project->range('1-2');
$build->name->range('Build v1.0{2}, Build v2.0{2}, Build v3.0{1}');
$build->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 准备测试数据
$build1 = new stdclass();
$build1->id = 1;
$build1->product = 1;
$build1->project = 1;
$build1->system = 1;
$build1->name = 'Build v1.0';

$data1 = new stdclass();
$data1->released = true;
$data1->archived = false;
$data1->startdate = '2024-01-15 10:00:00';
$data1->description = 'Regular release with new features';
$data1->releasedate = '2024-01-15';

$result1 = $convertTest->createReleaseTest($build1, $data1, array(), array());
r($result1) && p() && e(1); // 步骤1：正常创建发布版本

$data2 = new stdclass();
$data2->released = true;
$data2->archived = true;
$data2->startdate = '2024-02-01 10:00:00';
$data2->description = 'Archived release';

r($convertTest->createReleaseTest($build1, $data2, array(), array())) && p() && e(1); // 步骤2：创建已归档的发布版本

$data3 = new stdclass();
$data3->released = false;
$data3->archived = false;
$data3->startdate = '2024-03-01 10:00:00';
$data3->description = 'Waiting for release';

r($convertTest->createReleaseTest($build1, $data3, array(), array())) && p() && e(1); // 步骤3：创建等待发布的版本

$data4 = new stdclass();
$data4->released = true;
$data4->archived = false;
$data4->startdate = '2024-04-01 10:00:00';
$data4->description = 'Release with story issues';

$releaseIssue1 = array();
$releaseIssue1[] = (object)array('issueid' => 1, 'relation' => 'IssueFixVersion');
$releaseIssue1[] = (object)array('issueid' => 2, 'relation' => 'IssueFixVersion');

$issueList1 = array(
    1 => array('BID' => 101, 'BType' => 'zstory'),
    2 => array('BID' => 102, 'BType' => 'zstory')
);

r($convertTest->createReleaseTest($build1, $data4, $releaseIssue1, $issueList1)) && p() && e(1); // 步骤4：创建带关联需求的发布版本

$data5 = new stdclass();
$data5->released = true;
$data5->archived = false;
$data5->startdate = '2024-05-01 10:00:00';
$data5->description = 'Release with bug issues';

$releaseIssue2 = array();
$releaseIssue2[] = (object)array('issueid' => 3, 'relation' => 'IssueFixVersion');
$releaseIssue2[] = (object)array('issueid' => 4, 'relation' => 'IssueFixVersion');

$issueList2 = array(
    3 => array('BID' => 201, 'BType' => 'zbug'),
    4 => array('BID' => 202, 'BType' => 'zbug')
);

r($convertTest->createReleaseTest($build1, $data5, $releaseIssue2, $issueList2)) && p() && e(1); // 步骤5：创建带关联缺陷的发布版本