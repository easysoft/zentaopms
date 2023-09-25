#!/usr/bin/env php
<?php
/**

title=测试 productplan->getList()
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/productplan.class.php';

zdTable('productplan')->config('productplan')->gen(20);
zdTable('product')->config('product')->gen(20);
zdTable('story')->gen(20);
zdTable('bug')->gen(20);
$productplan = new Productplan('admin');

$dataList = array();
$dataList[0] = $productplan->getList();
$dataList[1] = $productplan->getList(1, 0, 'all', null, 'begin_desc');
$dataList[2] = $productplan->getList(1, 0, 'all', null, 'begin_desc', 'kipparent');
$dataList[3] = $productplan->getList(1, 0, 'undone', null, 'begin_desc');
$dataList[4] = $productplan->getList(1, 0, 'wait', null, 'begin_desc');
$dataList[5] = $productplan->getList(1, 0, 'doing', null, 'begin_desc');

r($dataList[0]) && p()                      && e('0');         // 获取空数据
r($dataList[1]) && p('1:id,product,status') && e('1,1,wait');  // 获取product=1的所有的计划
r($dataList[2]) && p('2:id,product,status') && e('2,1,doing'); // 获取product=1的所有的非父计划
r($dataList[3]) && p('1:id;2:status')       && e('1;doing');   // 获取product=1的所有未完成的计划
r($dataList[4]) && p('1:id,product,status') && e('1,1,wait');  // 获取product=1的所有未开始的计划
r($dataList[5]) && p('2:title')             && e('计划2');     // 获取product=1的所有进行中的计划
