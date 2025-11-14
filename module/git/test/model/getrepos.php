#!/usr/bin/env php
<?php

/**

title=测试 gitModel::getRepos();
timeout=0
cid=16547

- 步骤1：正常情况下获取Git仓库路径列表 @8
- 步骤2：验证返回的第一个仓库路径格式属性1 @https://gitlabdev.qc.oop.cc/root/unittest11
- 步骤3：验证getRepos方法返回数组类型 @1
- 步骤4：验证路径数组包含预期的URL格式 @1
- 步骤5：验证方法执行的稳定性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

zenData('pipeline')->gen(1);
zenData('repo')->loadYaml('repo')->gen(10);
su('admin');

$gitTest = new gitTest();

$repos = $gitTest->getReposTest();
r(count($repos)) && p() && e('8'); // 步骤1：正常情况下获取Git仓库路径列表

r($repos) && p('1') && e('https://gitlabdev.qc.oop.cc/root/unittest11'); // 步骤2：验证返回的第一个仓库路径格式

r(is_array($repos)) && p() && e('1'); // 步骤3：验证getRepos方法返回数组类型

r(strpos($repos[0], 'https://') === 0) && p() && e('1'); // 步骤4：验证路径数组包含预期的URL格式

$repos2 = $gitTest->getReposTest();
r(count($repos2) === count($repos)) && p() && e('1'); // 步骤5：验证方法执行的稳定性