#!/usr/bin/env php
<?php

/**

title=测试 bugModel::updateLinkedCommits();
timeout=0
cid=15408

- 步骤1:正常情况 @1
- 步骤2:bugID为0 @1
- 步骤3:repoID为0 @1
- 步骤4:revisions为空 @1
- 步骤5:bug不存在 @1
- 步骤6:多个revision @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zendata('bug')->loadYaml('bug', false, 2)->gen(10);
zendata('repohistory')->loadYaml('repohistory', false, 2)->gen(20);

$bugTest = new bugModelTest();

r($bugTest->updateLinkedCommitsTest(1, 1, array(1, 2))) && p() && e('1'); // 步骤1:正常情况
r($bugTest->updateLinkedCommitsTest(0, 1, array(1))) && p() && e('1'); // 步骤2:bugID为0
r($bugTest->updateLinkedCommitsTest(1, 0, array(1))) && p() && e('1'); // 步骤3:repoID为0
r($bugTest->updateLinkedCommitsTest(1, 1, array())) && p() && e('1'); // 步骤4:revisions为空
r($bugTest->updateLinkedCommitsTest(999, 1, array(1))) && p() && e('1'); // 步骤5:bug不存在
r($bugTest->updateLinkedCommitsTest(2, 1, array(3, 4, 5))) && p() && e('1'); // 步骤6:多个revision