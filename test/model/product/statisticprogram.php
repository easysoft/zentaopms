#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->statisticProgram();
cid=1
pid=1

测试获取统计数据集中产品1的stats信息 >> 正常产品1,0,5,9
测试获取统计数据集中产品12的stats信息 >> 正常产品12,0,0,3
测试获取统计数据集中产品45的stats信息 >> 多分支产品45,2,0,3
测试获取统计数据集中产品89的stats信息 >> 多平台产品89,0,0,3
测试获取统计数据集中产品100的stats信息 >> 多平台产品100,0,0,3
测试获取统计数据集中产品2的stats信息 >> 正常产品2,0,2,4
测试获取统计数据集中产品13的stats信息 >> 正常产品13,0,0,3
测试获取统计数据集中产品46的stats信息 >> 多分支产品46,2,0,3
测试获取统计数据集中产品90的stats信息 >> 多平台产品90,0,0,3
测试获取统计数据集中产品3的stats信息 >> 正常产品3,0,0,4
测试获取统计数据集中产品4的stats信息 >> 正常产品4,0,0,4
测试获取统计数据集中产品5的stats信息 >> 正常产品5,0,0,4
测试获取统计数据集中产品6的stats信息 >> 正常产品6,0,0,4
测试获取统计数据集中产品7的stats信息 >> 正常产品7,0,0,4
测试获取统计数据集中产品8的stats信息 >> 正常产品8,0,0,4
测试获取统计数据集中产品9的stats信息 >> 正常产品9,0,0,4

*/

$product = new productTest('admin');

$productStats = $product->getStatsTest();

r($product->statisticProgramTest($productStats, '0')) && p('1:name,plans,releases,bugs')   && e('正常产品1,0,5,9');     // 测试获取统计数据集中产品1的stats信息
r($product->statisticProgramTest($productStats, '0')) && p('12:name,plans,releases,bugs')  && e('正常产品12,0,0,3');    // 测试获取统计数据集中产品12的stats信息
r($product->statisticProgramTest($productStats, '0')) && p('45:name,plans,releases,bugs')  && e('多分支产品45,2,0,3');  // 测试获取统计数据集中产品45的stats信息
r($product->statisticProgramTest($productStats, '0')) && p('89:name,plans,releases,bugs')  && e('多平台产品89,0,0,3');  // 测试获取统计数据集中产品89的stats信息
r($product->statisticProgramTest($productStats, '0')) && p('100:name,plans,releases,bugs') && e('多平台产品100,0,0,3'); // 测试获取统计数据集中产品100的stats信息
r($product->statisticProgramTest($productStats, '1')) && p('2:name,plans,releases,bugs')   && e('正常产品2,0,2,4');     // 测试获取统计数据集中产品2的stats信息
r($product->statisticProgramTest($productStats, '1')) && p('13:name,plans,releases,bugs')  && e('正常产品13,0,0,3');    // 测试获取统计数据集中产品13的stats信息
r($product->statisticProgramTest($productStats, '1')) && p('46:name,plans,releases,bugs')  && e('多分支产品46,2,0,3');  // 测试获取统计数据集中产品46的stats信息
r($product->statisticProgramTest($productStats, '1')) && p('90:name,plans,releases,bugs')  && e('多平台产品90,0,0,3');  // 测试获取统计数据集中产品90的stats信息
r($product->statisticProgramTest($productStats, '2')) && p('3:name,plans,releases,bugs')   && e('正常产品3,0,0,4');     // 测试获取统计数据集中产品3的stats信息
r($product->statisticProgramTest($productStats, '3')) && p('4:name,plans,releases,bugs')   && e('正常产品4,0,0,4');     // 测试获取统计数据集中产品4的stats信息
r($product->statisticProgramTest($productStats, '4')) && p('5:name,plans,releases,bugs')   && e('正常产品5,0,0,4');     // 测试获取统计数据集中产品5的stats信息
r($product->statisticProgramTest($productStats, '5')) && p('6:name,plans,releases,bugs')   && e('正常产品6,0,0,4');     // 测试获取统计数据集中产品6的stats信息
r($product->statisticProgramTest($productStats, '6')) && p('7:name,plans,releases,bugs')   && e('正常产品7,0,0,4');     // 测试获取统计数据集中产品7的stats信息
r($product->statisticProgramTest($productStats, '7')) && p('8:name,plans,releases,bugs')   && e('正常产品8,0,0,4');     // 测试获取统计数据集中产品8的stats信息
r($product->statisticProgramTest($productStats, '8')) && p('9:name,plans,releases,bugs')   && e('正常产品9,0,0,4');     // 测试获取统计数据集中产品9的stats信息
