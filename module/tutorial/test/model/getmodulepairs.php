#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

su('admin');

/**

title=测试 tutorialModel->getModulePairs();
cid=1
pid=1

测试是否能拿到数据 >> Test module

*/

$tutorial = new tutorialTest();

r($tutorial->getModulePairsTest()) && p('1') && e('Test module'); //测试是否能拿到数据
