#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitfox.class.php';
su('admin');

/**

title=测试gitfoxModel->gitPairs();
timeout=0
cid=1

- 获取GitFox id为1的名字。属性1 @GitFox服务器
- 获取GitFox 列表数量。 @1

*/

zdTable('pipeline')->gen(7);
$gitfox = new gitfoxTest();

$gitfoxPairs = $gitfox->getPairs();
r($gitfoxPairs)        && p(7) && e('GitFox服务器'); // 获取GitFox id为1的名字。
r(count($gitfoxPairs)) && p()  && e('1');            // 获取GitFox 列表数量。
