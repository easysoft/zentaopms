#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('risk')->gen('20');
zenData('userquery')->loadYaml('userquery')->gen('1');
zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->getRisksBySearch();
cid=17300

- 测试通过搜索获取 query id 0 类型 work id_desc 风险id @19,16,13,10,7,4,1
- 测试通过搜索获取 query id 0 类型 work id_desc 风险数量 @7
- 测试通过搜索获取 query id 0 类型 work id_asc 风险id @1,4,7,10,13,16,19
- 测试通过搜索获取 query id 0 类型 work id_asc 风险数量 @7
- 测试通过搜索获取 query id 0 类型 contribute id_desc 风险id @19,17,15,13,11,9,7,5,3,1
- 测试通过搜索获取 query id 0 类型 contribute id_desc 风险数量 @10
- 测试通过搜索获取 query id 0 类型 contribute id_asc 风险id @1,3,5,7,9,11,13,15,17,19
- 测试通过搜索获取 query id 0 类型 contribute id_asc 风险数量 @10
- 测试通过搜索获取 query id 5 类型 work id_desc 风险id @19,16,13,10,7,4,1
- 测试通过搜索获取 query id 5 类型 work id_desc 风险数量 @7
- 测试通过搜索获取 query id 5 类型 work id_asc 风险id @1,4,7,10,13,16,19
- 测试通过搜索获取 query id 5 类型 work id_asc 风险数量 @7
- 测试通过搜索获取 query id 5 类型 contribute id_desc 风险id @19,17,15,13,11,9,7,5,3,1
- 测试通过搜索获取 query id 5 类型 contribute id_desc 风险数量 @10
- 测试通过搜索获取 query id 5 类型 contribute id_asc 风险id @1,3,5,7,9,11,13,15,17,19
- 测试通过搜索获取 query id 5 类型 contribute id_asc 风险数量 @10

*/

$my      = new myTest();
$queryID = array(0, 1);
$type    = array('work', 'contribute');
$orderBy = array('id_desc', 'id_asc');

global $tester;
$tester->session->set('workRiskQuery', "`name` like '%风险%'");
$tester->session->set('contributeRiskQuery', "`name` like '%风险%'");

$tasks1 = $my->getRisksBySearchTest($queryID[0], $type[0], $orderBy[0]);
$tasks2 = $my->getRisksBySearchTest($queryID[0], $type[0], $orderBy[1]);
$tasks3 = $my->getRisksBySearchTest($queryID[0], $type[1], $orderBy[0]);
$tasks4 = $my->getRisksBySearchTest($queryID[0], $type[1], $orderBy[1]);
$tasks5 = $my->getRisksBySearchTest($queryID[1], $type[0], $orderBy[0]);
$tasks6 = $my->getRisksBySearchTest($queryID[1], $type[0], $orderBy[1]);
$tasks7 = $my->getRisksBySearchTest($queryID[1], $type[1], $orderBy[0]);
$tasks8 = $my->getRisksBySearchTest($queryID[1], $type[1], $orderBy[1]);

r(implode(',', $tasks1)) && p() && e('19,16,13,10,7,4,1');        // 测试通过搜索获取 query id 0 类型 work id_desc 风险id
r(count($tasks1))        && p() && e('7');                        // 测试通过搜索获取 query id 0 类型 work id_desc 风险数量
r(implode(',', $tasks2)) && p() && e('1,4,7,10,13,16,19');        // 测试通过搜索获取 query id 0 类型 work id_asc 风险id
r(count($tasks2))        && p() && e('7');                        // 测试通过搜索获取 query id 0 类型 work id_asc 风险数量
r(implode(',', $tasks3)) && p() && e('19,17,15,13,11,9,7,5,3,1'); // 测试通过搜索获取 query id 0 类型 contribute id_desc 风险id
r(count($tasks3))        && p() && e('10');                       // 测试通过搜索获取 query id 0 类型 contribute id_desc 风险数量
r(implode(',', $tasks4)) && p() && e('1,3,5,7,9,11,13,15,17,19'); // 测试通过搜索获取 query id 0 类型 contribute id_asc 风险id
r(count($tasks4))        && p() && e('10');                       // 测试通过搜索获取 query id 0 类型 contribute id_asc 风险数量
r(implode(',', $tasks5)) && p() && e('19,16,13,10,7,4,1');        // 测试通过搜索获取 query id 5 类型 work id_desc 风险id
r(count($tasks5))        && p() && e('7');                        // 测试通过搜索获取 query id 5 类型 work id_desc 风险数量
r(implode(',', $tasks6)) && p() && e('1,4,7,10,13,16,19');        // 测试通过搜索获取 query id 5 类型 work id_asc 风险id
r(count($tasks6))        && p() && e('7');                        // 测试通过搜索获取 query id 5 类型 work id_asc 风险数量
r(implode(',', $tasks7)) && p() && e('19,17,15,13,11,9,7,5,3,1'); // 测试通过搜索获取 query id 5 类型 contribute id_desc 风险id
r(count($tasks7))        && p() && e('10');                       // 测试通过搜索获取 query id 5 类型 contribute id_desc 风险数量
r(implode(',', $tasks8)) && p() && e('1,3,5,7,9,11,13,15,17,19'); // 测试通过搜索获取 query id 5 类型 contribute id_asc 风险id
r(count($tasks8))        && p() && e('10');                       // 测试通过搜索获取 query id 5 类型 contribute id_asc 风险数量
