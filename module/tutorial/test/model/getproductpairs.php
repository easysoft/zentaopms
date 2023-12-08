#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

su('admin');

/**

title=测试 tutorialModel->getProductPairs();
cid=1
pid=1

测试是否能拿到数据 >> Test product

*/

$tutorial = new tutorialTest();

r($tutorial->getProductPairsTest()) && p('1') && e('Test product'); //测试是否能拿到数据
