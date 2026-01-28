#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getTutorialed();
timeout=0
cid=19493

- 测试是否能拿到 admin 的数据 @3
- 测试是否能拿到 user1 的数据 @4
- 测试是否能拿到 user2 的数据 @0
- 测试是否能拿到 user3 的数据 @0
- 测试是否能拿到 user4 的数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('config')->loadYaml('config')->gen(16);

$tutorial = new tutorialModelTest();

su('admin');
r($tutorial->getTutorialedTest()) && p() && e('3'); // 测试是否能拿到 admin 的数据

su('user1');
r($tutorial->getTutorialedTest()) && p() && e('4'); // 测试是否能拿到 user1 的数据

su('user2');
r($tutorial->getTutorialedTest()) && p() && e('0'); // 测试是否能拿到 user2 的数据

su('user3');
r($tutorial->getTutorialedTest()) && p() && e('0'); // 测试是否能拿到 user3 的数据

su('user4');
r($tutorial->getTutorialedTest()) && p() && e('0'); // 测试是否能拿到 user4 的数据