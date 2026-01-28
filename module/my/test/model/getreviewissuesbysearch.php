#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$reviewissue = zenData('reviewissue');
$reviewissue->project->range('1');
$reviewissue->review->range('1-5');
$reviewissue->type->range('review');
$reviewissue->opinionDate->range('`2025-01-01`');
$reviewissue->resolutionDate->range('`2025-01-01`');
$reviewissue->createdDate->range('`2025-01-01`');
$reviewissue->assignedTo->range('admin,user1,user2');
$reviewissue->gen(20);
zenData('userquery')->loadYaml('userquery')->gen('1');
zenData('user')->gen('1');

su('admin');

/**

title=测试 myModel->getReviewissuesBySearch();
cid=1

- 测试通过搜索获取 query id 0 类型 work id_desc 评审意见id @19,16,13,10,7,4,1
- 测试通过搜索获取 query id 0 类型 work id_desc 评审意见数量 @7
- 测试通过搜索获取 query id 0 类型 work id_asc 评审意见id @1,4,7,10,13,16,19
- 测试通过搜索获取 query id 0 类型 work id_asc 评审意见数量 @7
- 测试通过搜索获取 query id 5 类型 work id_desc 评审意见id @19,16,13,10,7,4,1
- 测试通过搜索获取 query id 5 类型 work id_desc 评审意见数量 @7
- 测试通过搜索获取 query id 5 类型 work id_asc 评审意见id @1,4,7,10,13,16,19
- 测试通过搜索获取 query id 5 类型 work id_asc 评审意见数量 @7

*/

$my      = new myModelTest();
$queryID = array(0, 1);
$type    = array('work', 'contribute');
$orderBy = array('id_desc', 'id_asc');

global $tester;
$tester->session->set('workReviewissueQuery', "`title` like '%问题%'");
$tester->session->set('contributeReviewissueQuery', "`title` like '%问题%'");

$tasks1 = $my->getReviewissuesBySearchTest($queryID[0], $type[0], $orderBy[0]);
$tasks2 = $my->getReviewissuesBySearchTest($queryID[0], $type[0], $orderBy[1]);
$tasks3 = $my->getReviewissuesBySearchTest($queryID[0], $type[0], $orderBy[0]);
$tasks4 = $my->getReviewissuesBySearchTest($queryID[0], $type[0], $orderBy[1]);

r(implode(',', $tasks1)) && p() && e('19,16,13,10,7,4,1'); // 测试通过搜索获取 query id 0 类型 work id_desc 评审意见id
r(count($tasks1))        && p() && e('7');                 // 测试通过搜索获取 query id 0 类型 work id_desc 评审意见数量
r(implode(',', $tasks2)) && p() && e('1,4,7,10,13,16,19'); // 测试通过搜索获取 query id 0 类型 work id_asc 评审意见id
r(count($tasks2))        && p() && e('7');                 // 测试通过搜索获取 query id 0 类型 work id_asc 评审意见数量
r(implode(',', $tasks3)) && p() && e('19,16,13,10,7,4,1'); // 测试通过搜索获取 query id 5 类型 work id_desc 评审意见id
r(count($tasks3))        && p() && e('7');                 // 测试通过搜索获取 query id 5 类型 work id_desc 评审意见数量
r(implode(',', $tasks4)) && p() && e('1,4,7,10,13,16,19'); // 测试通过搜索获取 query id 5 类型 work id_asc 评审意见id
r(count($tasks4))        && p() && e('7');                 // 测试通过搜索获取 query id 5 类型 work id_asc 评审意见数量
