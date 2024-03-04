#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->createLink();
timeout=0
cid=1

- 测试entry参数 @repo-showSyncCommit-1-.html?repoPath=ZXh0ZW50aW9u
- 测试file参数 @repo-showSyncCommit-5-.html?repoPath=YWJjLnBocA==
- 测试root参数 @repo-showSyncCommit-13-.html?repoPath=bW9kdWxlJTJGcmVwbyUyRmpz

*/

$repo = new repoTest();
$repo->objectModel->config->requestType = 'PATH_INFO';

$method = 'showSyncCommit';
$params = array('repoID=1&entry=ZXh0ZW50aW9u', 'repoID=5&file=YWJjLnBocA==', 'repoID=13&root=bW9kdWxlJTJGcmVwbyUyRmpz');

r($repo->createLinkTest($method, $params[0])) && p() && e('repo-showSyncCommit-1-.html?repoPath=ZXh0ZW50aW9u'); //测试entry参数
r($repo->createLinkTest($method, $params[1])) && p() && e('repo-showSyncCommit-5-.html?repoPath=YWJjLnBocA=='); //测试file参数
r($repo->createLinkTest($method, $params[2])) && p() && e('repo-showSyncCommit-13-.html?repoPath=bW9kdWxlJTJGcmVwbyUyRmpz'); //测试root参数