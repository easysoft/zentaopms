#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 myModel->getReviewingFeedbacks();
timeout=0
cid=17293

- 测试获取排序 id_desc 的审批id。 @15,8,1

- 测试获取排序 id_asc 的审批id。 @1,8,15

- 测试获取排序 id_desc 的审批是否存在。 @exist
- 测试获取排序 id_asc 的审批是否存在。 @exist
- 测试获取排序 prodcut_desc 的审批id。 @15,8,1

*/

zenData('user')->gen('2');
zenData('feedback')->gen('20');
zenData('product')->gen('20');

su('admin');

global $config;
$config->edition = 'ipd';

$my = new myModelTest();
$orderBy    = array('id_desc', 'id_asc');
$checkExist = array(false, true);

r($my->getReviewingFeedbacksTest($orderBy[0], $checkExist[0])) && p() && e(`15,8,1`);    // 测试获取排序 id_desc 的审批id。
r($my->getReviewingFeedbacksTest($orderBy[1], $checkExist[0])) && p() && e(`1,8,15`);    // 测试获取排序 id_asc 的审批id。
r($my->getReviewingFeedbacksTest($orderBy[0], $checkExist[1])) && p() && e('exist');     // 测试获取排序 id_desc 的审批是否存在。
r($my->getReviewingFeedbacksTest($orderBy[1], $checkExist[1])) && p() && e('exist');     // 测试获取排序 id_asc 的审批是否存在。
r($my->getReviewingFeedbacksTest('product_desc', $checkExist[0])) && p() && e(`15,8,1`); // 测试获取排序 prodcut_desc 的审批id。
