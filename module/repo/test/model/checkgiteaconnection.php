#!/usr/bin/env php
<?php

/**

title=测试 repoModel::checkGiteaConnection();
timeout=0
cid=18032

- 步骤1：测试参数全为空的情况 @0
- 步骤2：测试name为空的情况 @0
- 步骤3：测试serviceProject为空的情况 @0
- 步骤4：测试非gitea类型SCM @0
- 步骤5：测试正常gitea参数但项目不存在 @0
- 步骤6：测试serviceHost为空但其他参数正常 @0
- 步骤7：测试空白字符参数组合 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repoTest = new repoTest();

r($repoTest->checkGiteaConnectionTest('', '', '', ''))                       && p() && e('0');      // 步骤1：测试参数全为空的情况
r($repoTest->checkGiteaConnectionTest('gitea', '', '1', 'test'))             && p() && e('0');      // 步骤2：测试name为空的情况
r($repoTest->checkGiteaConnectionTest('gitea', 'testname', '1', ''))         && p() && e('0');      // 步骤3：测试serviceProject为空的情况
r($repoTest->checkGiteaConnectionTest('git', 'testname', '1', 'testproject')) && p() && e('0');      // 步骤4：测试非gitea类型SCM
r($repoTest->checkGiteaConnectionTest('gitea', 'testname', '1', 'testproject')) && p() && e('0');    // 步骤5：测试正常gitea参数但项目不存在
r($repoTest->checkGiteaConnectionTest('gitea', 'testname', '', 'testproject')) && p() && e('0');     // 步骤6：测试serviceHost为空但其他参数正常
r($repoTest->checkGiteaConnectionTest('gitea', ' ', '1', ' '))               && p() && e('0');      // 步骤7：测试空白字符参数组合