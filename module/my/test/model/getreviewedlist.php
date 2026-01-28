#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->loadYaml('action_getreviewedlist')->gen('10');
zenData('story')->gen('10');
zenData('case')->gen('10');
zenData('user')->gen('10');
zenData('product')->gen('10');

su('admin');

/**

title=测试 myModel->getReviewedList();
timeout=0
cid=17289

- 测试获取排序 browse all id_desc 的审批列表。 @10,9

- 测试获取排序 browse all id_asc 的审批列表。 @9,10

- 测试获取排序 browse createdbyme id_desc 的审批列表。 @4,3,2,1

- 测试获取排序 browse createdbyme id_asc 的审批列表。 @1,2,3,4

- 测试获取排序 browse createdbyme 默认排序 的审批列表。 @4,3,2,1

*/

$my = new myModelTest();

$browseType = array('all', 'createdbyme');
$orderBy    = array('id_desc', 'id_asc', 'time_desc');

r($my->getReviewedListTest($browseType[0], $orderBy[0])) && p() && e('10,9');    // 测试获取排序 browse all id_desc 的审批列表。
r($my->getReviewedListTest($browseType[0], $orderBy[1])) && p() && e('9,10');    // 测试获取排序 browse all id_asc 的审批列表。
r($my->getReviewedListTest($browseType[1], $orderBy[0])) && p() && e('4,3,2,1'); // 测试获取排序 browse createdbyme id_desc 的审批列表。
r($my->getReviewedListTest($browseType[1], $orderBy[1])) && p() && e('1,2,3,4'); // 测试获取排序 browse createdbyme id_asc 的审批列表。
r($my->getReviewedListTest($browseType[1], $orderBy[2])) && p() && e('4,3,2,1'); // 测试获取排序 browse createdbyme 默认排序 的审批列表。
