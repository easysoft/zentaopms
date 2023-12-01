#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('action')->config('action_getreviewedlist')->gen('10');
zdTable('story')->gen('10');
zdTable('case')->gen('10');
zdTable('user')->gen('10');

su('admin');

/**

title=测试 myModel->getReviewedList();
cid=1
pid=1

*/

$my = new myTest();

$browseType = array('all', 'createdbyme');
$orderBy    = array('id_desc', 'id_asc');

r($my->getReviewedListTest($browseType[0], $orderBy[0])) && p() && e('10,9');    // 测试获取排序 browse all id_desc 的审批列表。
r($my->getReviewedListTest($browseType[0], $orderBy[1])) && p() && e('9,10');    // 测试获取排序 browse all id_asc 的审批列表。
r($my->getReviewedListTest($browseType[1], $orderBy[0])) && p() && e('4,3,2,1'); // 测试获取排序 browse createdbyme id_desc 的审批列表。
r($my->getReviewedListTest($browseType[1], $orderBy[1])) && p() && e('1,2,3,4'); // 测试获取排序 browse createdbyme id_asc 的审批列表。
