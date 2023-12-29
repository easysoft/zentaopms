#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->buildURL();
timeout=0
cid=1

- 生成svn的跳转地址 @svn-cat--1.html?repoUrl=dGVzdA==

*/

$repo = new repoTest();

r($repo->buildURLTest('cat', 'test', '1', 'svn')) && p() && e('svn-cat--1.html?repoUrl=dGVzdA=='); // 生成svn的跳转地址