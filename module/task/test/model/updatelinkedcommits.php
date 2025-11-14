#!/usr/bin/env php
<?php

/**

title=测试 taskModel::updateLinkedCommits();
timeout=0
cid=18855

- 步骤1：正常情况 @1
- 步骤2：任务ID为0 @1
- 步骤3：仓库ID为0 @1
- 步骤4：空的提交数组 @1
- 步骤5：不存在的任务ID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('task')->loadYaml('task', false, 2)->gen(10);
zenData('relation')->gen(0);

su('admin');

$taskTest = new taskTest();

r($taskTest->updateLinkedCommitsTest(1, 1, array(123, 456))) && p() && e('1'); // 步骤1：正常情况
r($taskTest->updateLinkedCommitsTest(0, 1, array(123))) && p() && e('1'); // 步骤2：任务ID为0
r($taskTest->updateLinkedCommitsTest(1, 0, array(123))) && p() && e('1'); // 步骤3：仓库ID为0
r($taskTest->updateLinkedCommitsTest(1, 1, array())) && p() && e('1'); // 步骤4：空的提交数组
r($taskTest->updateLinkedCommitsTest(999, 1, array(123))) && p() && e('1'); // 步骤5：不存在的任务ID