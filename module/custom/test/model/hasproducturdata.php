#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasProductURData();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('story')->gen(0);
zdTable('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->hasProductURDataTest()) && p() && e('0'); // 测试系统中无用户需求数据

$storyTable = zdTable('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('0');
$storyTable->gen(5);
r($customTester->hasProductURDataTest()) && p() && e('5'); // 测试系统中有用户需求数据
