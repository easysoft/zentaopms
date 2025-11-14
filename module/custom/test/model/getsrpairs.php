#!/usr/bin/env php
<?php
/**

title=测试 customModel->getSRPairs();
timeout=0
cid=15902

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->getSRPairsTest()) && p('1,2,3,4,5') && e('软件需求,研发需求,软需,故事,需求');  // 获取软需概念集合。
