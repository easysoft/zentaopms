#!/usr/bin/env php
<?php

/**

title=测试 bugModel::updateLinkedCommits();
timeout=0
cid=0

- 测试步骤1：正常情况下更新bug关联的提交记录 @1
- 测试步骤2：bugID为0的边界值情况 @1
- 测试步骤3：repoID为0的边界值情况 @1
- 测试步骤4：revisions为空数组的边界值情况 @1
- 测试步骤5：不存在的bugID情况 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 准备测试数据
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1{5}, 2{5}');
$bug->project->range('1{5}, 2{5}');
$bug->title->range('Bug Title {1-10}');
$bug->status->range('active{10}');
$bug->gen(10);

zenData('relation')->gen(0);

su('admin');

$bugTest = new bugTest();
r($bugTest->updateLinkedCommitsTest(1, 1, array('rev001', 'rev002'))) && p() && e('1'); // 测试步骤1：正常情况下更新bug关联的提交记录
r($bugTest->updateLinkedCommitsTest(0, 1, array('rev001'))) && p() && e('1'); // 测试步骤2：bugID为0的边界值情况
r($bugTest->updateLinkedCommitsTest(1, 0, array('rev001'))) && p() && e('1'); // 测试步骤3：repoID为0的边界值情况
r($bugTest->updateLinkedCommitsTest(1, 1, array())) && p() && e('1'); // 测试步骤4：revisions为空数组的边界值情况
r($bugTest->updateLinkedCommitsTest(999, 1, array('rev001'))) && p() && e('1'); // 测试步骤5：不存在的bugID情况