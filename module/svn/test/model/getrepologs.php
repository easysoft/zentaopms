#!/usr/bin/env php
<?php

/**

title=测试 svnModel::getRepoLogs();
timeout=0
cid=18716

- 步骤1：正常提交ID为0的情况 @0
- 步骤2：正常提交ID为1的情况 @0
- 步骤3：负数提交ID的边界情况 @0
- 步骤4：大数值提交ID的边界情况 @0
- 步骤5：测试空日志结果的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/svn.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(3);
su('admin');

$svnTest = new svnTest();

r($svnTest->getRepoLogsTest(0)) && p() && e('0'); // 步骤1：正常提交ID为0的情况
r($svnTest->getRepoLogsTest(1)) && p() && e('0'); // 步骤2：正常提交ID为1的情况
r($svnTest->getRepoLogsTest(-1)) && p() && e('0'); // 步骤3：负数提交ID的边界情况
r($svnTest->getRepoLogsTest(99999)) && p() && e('0'); // 步骤4：大数值提交ID的边界情况
r($svnTest->getRepoLogsTest(0)) && p() && e('0'); // 步骤5：测试空日志结果的情况