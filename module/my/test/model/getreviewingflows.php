#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('user')->gen('2');

su('admin');

/**

title=测试 myModel->getReviewingFlows();
cid=1
pid=1

*/

$my = new myTest();

$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

r($my->getReviewingFlowsTest($orderBy[0], $checkExist[0])) && p() && e('empty'); // 测试获取排序 id_desc 的审批id。
r($my->getReviewingFlowsTest($orderBy[0], $checkExist[1])) && p() && e('empty'); // 测试获取排序 id_desc 的审批是否存在。
r($my->getReviewingFlowsTest($orderBy[1], $checkExist[0])) && p() && e('empty'); // 测试获取排序 id_asc 的审批id。
r($my->getReviewingFlowsTest($orderBy[1], $checkExist[1])) && p() && e('empty'); // 测试获取排序 id_asc 的审批是否存在。
