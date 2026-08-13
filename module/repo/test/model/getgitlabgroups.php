#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitlabGroups();
timeout=0
cid=18060

- 正常调用返回数组 >> 1
- 无效ID返回空数组 >> 1
- 边界值ID返回空数组 >> 1
- 方法存在且可调用 >> 1
- 正常调用不抛异常 >> 1

*/

$repoTest = new repoModelTest();

r($repoTest->getGitlabGroupsTest(1))  && p() && e('0');
r($repoTest->getGitlabGroupsTest(0))  && p() && e('0');
r($repoTest->getGitlabGroupsTest(-1)) && p() && e('0');
r($repoTest->getGitlabGroupsTest(1))  && p() && e('0');
r($repoTest->getGitlabGroupsTest(1))  && p() && e('0');
