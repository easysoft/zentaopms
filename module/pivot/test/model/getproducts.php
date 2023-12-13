#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->getProducts();
cid=1
pid=1

测试是否能正常获取到产品数据                                              >> 1,正常产品1;2,正常产品2
测试是否能正常获取到产品1的计划数据                                       >> 1,2,3,4
测试是否能正常获取到产品2的计划数据                                       >> 2,5,6,7
测试是否能正常获取到产品3的计划数据                                       >> 3,8,9,10
产品4没有计划，所以此不存在                                               >> 0
测试是否能正常获取产品1的计划所关联的用户故事以及为关联的用户故事的数量。 >> 1
测试是否能正常获取产品2的计划关联用户故事的数量以及未关联用户故事的数量。 >> 1
测试是否能正常获取产品3的计划关联用户故事的数量以及未关联用户故事的数量。 >> 1
产品4没有计划，所以此不存在                                               >> 0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('product')->gen(4);
zdTable('productplan')->config('productplan')->gen(10);
zdTable('story')->config('story')->gen(10);

$pivot = new pivotTest();

$products = $pivot->getProducts('');

r($products) && p('1:id,name;2:id,name') && e('1,正常产品1;2,正常产品2');   //测试是否能正常获取到产品数据

$planStatistics = array();
foreach($products as $product)
{
    if(isset($product->plans)) $planStatistics[$product->id] = $product->plans;
}

r($planStatistics[1])        && p('1:id;2:id;3:id;4:id')  && e('1,2,3,4');      //测试是否能正常获取到产品1的计划数据
r($planStatistics[2])        && p('2:id;5:id;6:id;7:id')  && e('2,5,6,7');      //测试是否能正常获取到产品2的计划数据
r($planStatistics[3])        && p('3:id;8:id;9:id;10:id') && e('3,8,9,10');     //测试是否能正常获取到产品3的计划数据
r(isset($planStatistics[4])) && p('')                     && e('0');            //产品4没有计划，所以此不存在

r(isset($planStatistics[1][0]) && $planStatistics[1][0]->status['active'] == 1) && p('') && e('1');                                                                           //测试是否能正常获取产品1的计划所关联的用户故事以及为关联的用户故事的数量。
r(isset($planStatistics[2][7], $planStatistics[2][6]) && $planStatistics[2][7]->status['changing'] == 1 && $planStatistics[2][6]->status['active'] == 1 ) && p('') && e('1');   //测试是否能正常获取产品2的计划关联用户故事的数量以及未关联用户故事的数量。
r(isset($planStatistics[3][0]) && $planStatistics[3][0]->status['active'] == 1) && p('') && e('1');                                                                           //测试是否能正常获取产品3的计划关联用户故事的数量以及未关联用户故事的数量。
r(isset($planStatistics[4])) && p('') && e('0');                                                                                                                            //产品4没有计划，所以此不存在
