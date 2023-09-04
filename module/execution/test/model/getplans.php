#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
su('admin');

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(10);
zdTable('branch')->config('branch')->gen(10);
zdTable('productplan')->config('productplan')->gen(30);
zdTable('projectproduct')->config('projectproduct')->gen(10);

/**

title=测试executionModel->getPlans();
timeout=0
cid=1

*/

$productIdList   = array(1, 2, 3, 4, 5, 6);
$paramList       = array('', 'withMainPlan', 'skipParent', 'unexpired', 'noclosed', 'sortedByDate');
$executionIdList = array(0, 11);
$count           = array(0, 1);

$execution = new executionTest();
r($execution->getPlansTest($productIdList, $paramList[0], $executionIdList[0], $count[0])) && p('1:3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');        // 查询全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[0], $executionIdList[1], $count[0])) && p('1:2')  && e('计划1 /计划2 [2021-06-01 ~ 2021-06-30]'); // 查询迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[0], $executionIdList[0], $count[1])) && p()       && e('6');                                      // 查询全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[0], $executionIdList[1], $count[1])) && p()       && e('6');                                      // 查询迭代1关联计划数量
r($execution->getPlansTest($productIdList, $paramList[1], $executionIdList[0], $count[0])) && p('1:3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');        // 查询带主干的全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[1], $executionIdList[1], $count[0])) && p('1:2')  && e('计划1 /计划2 [2021-06-01 ~ 2021-06-30]'); // 查询带主干的迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[1], $executionIdList[0], $count[1])) && p()       && e('6');                                      // 查询带主干的全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[1], $executionIdList[1], $count[1])) && p()       && e('6');                                      // 查询带主干的迭代1关联计划数量
r($execution->getPlansTest($productIdList, $paramList[2], $executionIdList[0], $count[0])) && p('1:3')  && e('计划3 [2022-01-01 ~ 2022-01-30]');        // 查询非父计划全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[2], $executionIdList[1], $count[0])) && p('1:2')  && e('计划1 /计划2 [2021-06-01 ~ 2021-06-30]'); // 查询非父计划迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[2], $executionIdList[0], $count[1])) && p()       && e('6');                                      // 查询非父计划全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[2], $executionIdList[1], $count[1])) && p()       && e('6');                                      // 查询非父计划迭代1关联计划数量
r($execution->getPlansTest($productIdList, $paramList[3], $executionIdList[0], $count[0])) && p('5:15') && e('计划15 待定');                            // 查询未过期计划全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[3], $executionIdList[1], $count[0])) && p('4:10') && e('计划10 待定');                            // 查询未过期计划迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[3], $executionIdList[0], $count[1])) && p()       && e('2');                                      // 查询未过期计划全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[3], $executionIdList[1], $count[1])) && p()       && e('2');                                      // 查询未过期计划迭代1关联计划数量
r($execution->getPlansTest($productIdList, $paramList[4], $executionIdList[0], $count[0])) && p('3:7')  && e('计划7 [2021-06-01 ~ 2021-06-30]');        // 查询未关闭计划全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[4], $executionIdList[1], $count[0])) && p('1:2')  && e('计划1 /计划2 [2021-06-01 ~ 2021-06-30]'); // 查询未关闭计划迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[4], $executionIdList[0], $count[1])) && p()       && e('5');                                      // 查询未关闭计划全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[4], $executionIdList[1], $count[1])) && p()       && e('5');                                      // 查询未关闭计划迭代1关联计划数量
r($execution->getPlansTest($productIdList, $paramList[5], $executionIdList[0], $count[0])) && p('3:7')  && e('计划7 [2021-06-01 ~ 2021-06-30]');        // 查询按照日期升序计划全部执行关联计划信息
r($execution->getPlansTest($productIdList, $paramList[5], $executionIdList[1], $count[0])) && p('1:2')  && e('计划1 /计划2 [2021-06-01 ~ 2021-06-30]'); // 查询按照日期升序计划迭代1关联计划信息
r($execution->getPlansTest($productIdList, $paramList[5], $executionIdList[0], $count[1])) && p()       && e('6');                                      // 查询按照日期升序计划全部执行关联计划数量
r($execution->getPlansTest($productIdList, $paramList[5], $executionIdList[1], $count[1])) && p()       && e('6');                                      // 查询按照日期升序计划迭代1关联计划数量
