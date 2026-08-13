#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel->getGitlabFilesByPath();
timeout=0
cid=18059

- 方法返回数组类型 >> 1
- 空路径调用返回数组 >> 1
- public子路径调用返回数组 >> 1
- 不存在repo调用不抛异常 >> 1
- 不同branch调用返回数组 >> 1

*/

$repo = new repoModelTest();

r($repo->getGitlabFilesByPathTest(1, '', 'master'))       && p() && e('0');
r($repo->getGitlabFilesByPathTest(1, '', 'master'))       && p() && e('0');
r($repo->getGitlabFilesByPathTest(1, 'public', 'master')) && p() && e('0');
r($repo->getGitlabFilesByPathTest(999, '', 'master'))     && p() && e('0');
r($repo->getGitlabFilesByPathTest(1, '', 'branch1'))      && p() && e('0');
