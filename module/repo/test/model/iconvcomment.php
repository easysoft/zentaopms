#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->iconvComment();
timeout=0
cid=1

- 转换成utf8字符集 @test
- 转换成GBK字符集 @中文

*/

$repo = new repoTest();

r($repo->iconvCommentTest('test', 'utf-8')) && p() && e('test'); // 转换成utf8字符集
r($repo->iconvCommentTest('中文', 'GBK'))   && p() && e('中文'); // 转换成GBK字符集