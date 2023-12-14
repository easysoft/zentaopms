#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getProjectPairs();
cid=1
pid=1

- 测试是否能拿到数据属性2 @Test Project

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

su('admin');

$tutorial = new tutorialTest();

r($tutorial->getProjectPairsTest()) && p('2') && e('Test Project'); //测试是否能拿到数据
