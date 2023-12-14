#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getTutorialed();
cid=1

- 测试是否能拿到 admin 的数据 @3
- 测试是否能拿到 user1 的数据 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);
zdTable('config')->config('config')->gen(16);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getTutorialedTest()) && p() && e('3'); // 测试是否能拿到 admin 的数据

su('user1');
r($tutorial->getTutorialedTest()) && p() && e('4'); // 测试是否能拿到 user1 的数据
