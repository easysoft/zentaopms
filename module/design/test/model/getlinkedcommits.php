#!/usr/bin/env php
<?php

/**

title=测试 designModel::getLinkedCommits();
timeout=0
cid=15991

- 步骤1：正常情况查询设计提交关联数据 @2
- 步骤2：查询不存在的仓库ID @0
- 步骤3：查询不存在的修订号 @0
- 步骤4：查询空的修订号数组 @0
- 步骤5：查询多个修订号 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* Use the stable module-level yaml data and override only the fields required by this case. */
$repohistory = zenData('repohistory')->loadYaml('repohistory');
$repohistory->repo->range('1{5}');
$repohistory->revision->range('abc123,def456,ghi789,jkl012,mno345');
$repohistory->gen(5);

$relation = zenData('relation')->loadYaml('relation');
$relation->AID->range('1,2,3,4,5');
$relation->BID->range('1,2,3,4,5');
$relation->gen(5);

zenData('design')->loadYaml('design')->gen(5);

su('admin');

$designTest = new designModelTest();

r($designTest->getLinkedCommitsTest(1, array('abc123', 'def456'))) && p() && e('2'); // 步骤1：正常情况查询设计提交关联数据
r($designTest->getLinkedCommitsTest(999, array('abc123', 'def456'))) && p() && e('0'); // 步骤2：查询不存在的仓库ID
r($designTest->getLinkedCommitsTest(1, array('nonexist'))) && p() && e('0'); // 步骤3：查询不存在的修订号
r($designTest->getLinkedCommitsTest(1, array())) && p() && e('0'); // 步骤4：查询空的修订号数组
r($designTest->getLinkedCommitsTest(1, array('abc123', 'def456', 'ghi789'))) && p() && e('3'); // 步骤5：查询多个修订号
