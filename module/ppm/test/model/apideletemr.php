#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiDeleteMR();
timeout=0
cid=17226

- 测试步骤1：正常删除GitLab类型的MR @0
- 测试步骤2：删除不存在host的MR @0
- 测试步骤3：删除无效hostID的MR @0
- 测试步骤4：删除非GitLab类型的MR @0
- 测试步骤5：删除已关闭状态的MR @0
- 测试步骤6：删除负数hostID的MR @0
- 测试步骤7：删除空项目ID的MR @0
- 测试步骤8：删除负数MRID的MR @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('gitlab1,gitlab2,gitea1,gitea2,gogs1');
$pipeline->type->range('gitlab{2},gitea{2},gogs{1}');
$pipeline->url->range('http://gitlab1.com,http://gitlab2.com,http://gitea1.com,http://gitea2.com,http://gogs1.com');
$pipeline->account->range('admin{5}');
$pipeline->password->range('password{5}');
$pipeline->private->range('private{5}');
$pipeline->deleted->range('0{5}');
$pipeline->gen(5);

$mr = zenData('mr');
$mr->id->range('1-10');
$mr->hostID->range('1-5');
$mr->sourceProject->range('project1{2},project2{2},project3{2},project4{2},project5{2}');
$mr->sourceBranch->range('feature1,feature2,develop,main,master');
$mr->targetProject->range('project1{2},project2{2},project3{2},project4{2},project5{2}');
$mr->targetBranch->range('master{10}');
$mr->mriid->range('100-109');
$mr->title->range('Test MR 1,Test MR 2,Test MR 3,Test MR 4,Test MR 5,Test MR 6,Test MR 7,Test MR 8,Test MR 9,Test MR 10');
$mr->status->range('opened{5},closed{5}');
$mr->repoID->range('1-5');
$mr->deleted->range('0{10}');
$mr->gen(10);

$repo = zenData('repo');
$repo->loadYaml('repo')->gen(5);

su('admin');

$mrTest = new mrTest();

r($mrTest->apiDeleteMRTest(1, 'project1', 100)) && p() && e('0');                   // 测试步骤1：正常删除GitLab类型的MR
r($mrTest->apiDeleteMRTest(999, 'project1', 100)) && p() && e('0');                 // 测试步骤2：删除不存在host的MR
r($mrTest->apiDeleteMRTest(0, 'project1', 100)) && p() && e('0');                   // 测试步骤3：删除无效hostID的MR
r($mrTest->apiDeleteMRTest(3, 'project3', 102)) && p() && e('0');                   // 测试步骤4：删除非GitLab类型的MR
r($mrTest->apiDeleteMRTest(1, 'project1', 105)) && p() && e('0');                   // 测试步骤5：删除已关闭状态的MR
r($mrTest->apiDeleteMRTest(-1, 'project1', 100)) && p() && e('0');                  // 测试步骤6：删除负数hostID的MR
r($mrTest->apiDeleteMRTest(2, '', 101)) && p() && e('0');                           // 测试步骤7：删除空项目ID的MR
r($mrTest->apiDeleteMRTest(1, 'project1', -1)) && p() && e('0');                    // 测试步骤8：删除负数MRID的MR