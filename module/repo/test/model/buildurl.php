#!/usr/bin/env php
<?php

/**

title=测试 repoModel::buildURL();
timeout=0
cid=18030

- 测试步骤1：SVN系统cat方法构建URL @buildurl.php?m=svn&f=cat&url=&revision=1&repoUrl=dGVzdA==
- 测试步骤2：Git系统diff方法构建URL @buildurl.php?m=git&f=diff&url=&revision=123&repoUrl=cHJvamVjdC9yZXBv
- 测试步骤3：包含特殊字符的URL构建 @buildurl.php?m=svn&f=view&url=&revision=456&repoUrl=dGVzdCtwYXRoIHdpdGggc3BhY2Vz
- 测试步骤4：空revision参数测试 @buildurl.php?m=svn&f=cat&url=&revision=&repoUrl=c2ltcGxl
- 测试步骤5：长URL字符串测试 @buildurl.php?m=git&f=log&url=&revision=999&repoUrl=dmVyeS9sb25nL3BhdGgvdG8vc29tZS9yZXBvc2l0b3J5L3dpdGgvbWFueS9uZXN0ZWQvZGlyZWN0b3JpZXMvZmlsZS50eHQ=

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$repo = new repoModelTest();

r($repo->buildURLTest('cat', 'test', '1', 'svn')) && p() && e('buildurl.php?m=svn&f=cat&url=&revision=1&repoUrl=dGVzdA=='); // 测试步骤1：SVN系统cat方法构建URL
r($repo->buildURLTest('diff', 'project/repo', '123', 'git')) && p() && e('buildurl.php?m=git&f=diff&url=&revision=123&repoUrl=cHJvamVjdC9yZXBv'); // 测试步骤2：Git系统diff方法构建URL
r($repo->buildURLTest('view', 'test+path with spaces', '456', 'svn')) && p() && e('buildurl.php?m=svn&f=view&url=&revision=456&repoUrl=dGVzdCtwYXRoIHdpdGggc3BhY2Vz'); // 测试步骤3：包含特殊字符的URL构建
r($repo->buildURLTest('cat', 'simple', '', 'svn')) && p() && e('buildurl.php?m=svn&f=cat&url=&revision=&repoUrl=c2ltcGxl'); // 测试步骤4：空revision参数测试
r($repo->buildURLTest('log', 'very/long/path/to/some/repository/with/many/nested/directories/file.txt', '999', 'git')) && p() && e('buildurl.php?m=git&f=log&url=&revision=999&repoUrl=dmVyeS9sb25nL3BhdGgvdG8vc29tZS9yZXBvc2l0b3J5L3dpdGgvbWFueS9uZXN0ZWQvZGlyZWN0b3JpZXMvZmlsZS50eHQ='); // 测试步骤5：长URL字符串测试
