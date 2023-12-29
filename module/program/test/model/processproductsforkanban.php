#!/usr/bin/env php
<?php
/**

title=测试 programModel::processProductsForKanban();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
su('admin');

zdTable('project')->config('program')->gen(30);
zdTable('product')->config('product')->gen(30);
zdTable('task')->gen(0);
zdTable('projectproduct')->gen(0);
zdTable('productplan')->gen(0);
zdTable('release')->gen(0);
zdTable('team')->gen(0);

global $app;
$app->rawModule = 'program';

$programTester = new programTest();
$productGroup  = $programTester->processProductsForKanbanTest();

r(count($productGroup)) && p()             && e('9');     // 获取产品的数量
r($productGroup[1][0])  && p('name')       && e('产品1'); // 获取产品的名字
r($productGroup[1][0])  && p('plans:0')    && e('``');    // 获取产品1的计划
r($productGroup[1][0])  && p('releases:0') && e('``');    // 获取产品1的发布
