#!/usr/bin/env php
<?php

/**

title=测试 repoModel::createLink();
timeout=0
cid=18038

- 测试PATH_INFO请求类型无路径参数 @repo-showSyncCommit-1.html
- 测试entry参数转换 @repo-showSyncCommit-1-.html?repoPath=ZXh0ZW50aW9u
- 测试file参数转换 @repo-showSyncCommit-5-.html?repoPath=YWJjLnBocA==
- 测试root参数转换 @repo-showSyncCommit-13-.html?repoPath=bW9kdWxlJTJGcmVwbyUyRmpz
- 测试无路径参数的正常情况 @repo-browse-2.html
- 测试path参数转换 @repo-diff-3-.html?repoPath=dGVzdC50eHQ=
- 测试viewType参数处理 @repo-view-4-.json?repoPath=dmlld19maWxl
- 测试空参数情况 @repo-showSyncCommit.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repo = new repoTest();

r($repo->createLinkTest('showSyncCommit', 'repoID=1')) && p() && e('repo-showSyncCommit-1.html'); // 测试PATH_INFO请求类型无路径参数
r($repo->createLinkTest('showSyncCommit', 'repoID=1&entry=ZXh0ZW50aW9u')) && p() && e('repo-showSyncCommit-1-.html?repoPath=ZXh0ZW50aW9u'); // 测试entry参数转换
r($repo->createLinkTest('showSyncCommit', 'repoID=5&file=YWJjLnBocA==')) && p() && e('repo-showSyncCommit-5-.html?repoPath=YWJjLnBocA=='); // 测试file参数转换
r($repo->createLinkTest('showSyncCommit', 'repoID=13&root=bW9kdWxlJTJGcmVwbyUyRmpz')) && p() && e('repo-showSyncCommit-13-.html?repoPath=bW9kdWxlJTJGcmVwbyUyRmpz'); // 测试root参数转换
r($repo->createLinkTest('browse', 'repoID=2')) && p() && e('repo-browse-2.html'); // 测试无路径参数的正常情况
r($repo->createLinkTest('diff', 'repoID=3&path=dGVzdC50eHQ=')) && p() && e('repo-diff-3-.html?repoPath=dGVzdC50eHQ='); // 测试path参数转换
r($repo->createLinkTest('view', 'repoID=4&entry=dmlld19maWxl', 'json')) && p() && e('repo-view-4-.json?repoPath=dmlld19maWxl'); // 测试viewType参数处理
r($repo->createLinkTest('showSyncCommit', '')) && p() && e('repo-showSyncCommit.html'); // 测试空参数情况