#!/usr/bin/env php
<?php

/**

title=测试 repoModel::buildURL();
timeout=0
cid=18030

- 测试步骤1：SVN系统cat方法构建URL @svn-cat--1.html?repoUrl=dGVzdA==
- 测试步骤2：Git系统diff方法构建URL @git-diff--123.html?repoUrl=cHJvamVjdC9yZXBv
- 测试步骤3：包含特殊字符的URL构建 @svn-view--456.html?repoUrl=dGVzdCtwYXRoIHdpdGggc3BhY2Vz
- 测试步骤4：空revision参数测试 @svn-cat--.html?repoUrl=c2ltcGxl
- 测试步骤5：长URL字符串测试 @git-log--999.html?repoUrl=dmVyeS9sb25nL3BhdGgvdG8vc29tZS9yZXBvc2l0b3J5L3dpdGgvbWFueS9uZXN0ZWQvZGlyZWN0b3JpZXMvZmlsZS50eHQ=

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

r($repo->buildURLTest('cat', 'test', '1', 'svn')) && p() && e('svn-cat--1.html?repoUrl=dGVzdA=='); // 测试步骤1：SVN系统cat方法构建URL
r($repo->buildURLTest('diff', 'project/repo', '123', 'git')) && p() && e('git-diff--123.html?repoUrl=cHJvamVjdC9yZXBv'); // 测试步骤2：Git系统diff方法构建URL
r($repo->buildURLTest('view', 'test+path with spaces', '456', 'svn')) && p() && e('svn-view--456.html?repoUrl=dGVzdCtwYXRoIHdpdGggc3BhY2Vz'); // 测试步骤3：包含特殊字符的URL构建
r($repo->buildURLTest('cat', 'simple', '', 'svn')) && p() && e('svn-cat--.html?repoUrl=c2ltcGxl'); // 测试步骤4：空revision参数测试
r($repo->buildURLTest('log', 'very/long/path/to/some/repository/with/many/nested/directories/file.txt', '999', 'git')) && p() && e('git-log--999.html?repoUrl=dmVyeS9sb25nL3BhdGgvdG8vc29tZS9yZXBvc2l0b3J5L3dpdGgvbWFueS9uZXN0ZWQvZGlyZWN0b3JpZXMvZmlsZS50eHQ='); // 测试步骤5：长URL字符串测试