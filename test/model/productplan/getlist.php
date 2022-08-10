#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=cid=1
cid=1
pid=1

获取空数据 >> 0
获取product=1的所有的计划 >> 1;1;wait
获取product=1的所有的非父计划 >> 2;1;wait
获取product=1的所有未完成的计划 >> 3;1;wait
获取product=1的所有未开始的计划 >> 3;1;wait
获取product=1的所有进行中的计划 >> 101;1;doing
获取product=2的所有已完成的计划 >> 103;2;done
获取product=1的所有已关闭的计划 >> 102;1;closed

*/

$productplan = new Productplan('admin');

$dataList = array();
$dataList[0] = $productplan->getList();
$dataList[1] = $productplan->getList(1, 0, 'all', null, 'begin_desc');
$dataList[2] = $productplan->getList(1, 0, 'all', null, 'begin_desc', 'kipparent');
$dataList[3] = $productplan->getList(1, 0, 'undone', null, 'begin_desc');
$dataList[4] = $productplan->getList(1, 0, 'wait', null, 'begin_desc');
$dataList[5] = $productplan->getList(1, 0, 'doing', null, 'begin_desc');
$dataList[6] = $productplan->getList(2, 0, 'done', null, 'begin_desc');
$dataList[7] = $productplan->getList(1, 0, 'closed', null, 'begin_desc');

r($dataList[0]) && p()                                && e('0');            // 获取空数据
r($dataList[1]) && p('1:id;1:product;1:status')       && e('1;1;wait');     // 获取product=1的所有的计划
r($dataList[2]) && p('2:id;2:product;2:status')       && e('2;1;wait');     // 获取product=1的所有的非父计划
r($dataList[3]) && p('3:id;3:product;3:status')       && e('3;1;wait');     // 获取product=1的所有未完成的计划
r($dataList[4]) && p('3:id;3:product;3:status')       && e('3;1;wait');     // 获取product=1的所有未开始的计划
r($dataList[5]) && p('101:id;101:product;101:status') && e('101;1;doing');  // 获取product=1的所有进行中的计划
r($dataList[6]) && p('103:id;103:product;103:status') && e('103;2;done');   // 获取product=2的所有已完成的计划
r($dataList[7]) && p('102:id;102:product;102:status') && e('102;1;closed'); // 获取product=1的所有已关闭的计划
?>