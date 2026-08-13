#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitlabProjects();
timeout=0
cid=18061

- 正常gitlabID调用返回数组 >> 1
- IS_DEVELOPER过滤返回数组 >> 1
- ALL过滤返回数组 >> 1
- 不存在ID返回空数组 >> 1
- 无效ID返回空数组 >> 1

*/

$repoTest = new repoModelTest();

r($repoTest->getGitlabProjectsTest(1, ''))             && p() && e('0');
r($repoTest->getGitlabProjectsTest(1, 'IS_DEVELOPER')) && p() && e('0');
r($repoTest->getGitlabProjectsTest(1, 'ALL'))          && p() && e('0');
r($repoTest->getGitlabProjectsTest(999, ''))           && p() && e('0');
r($repoTest->getGitlabProjectsTest(0, ''))             && p() && e('0');
