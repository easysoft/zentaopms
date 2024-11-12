#!/usr/bin/env php
<?php
chdir(__DIR__);

/**

title=测试用例评审
timeout=0
cid=1

- 测试用例评审通过。
 - 测试结果 @测试用例评审通过
 - 最终测试状态 @SUCCESS

*/
include '../lib/testcase.ui.class.php';
zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);

/* 修改zt_config表中控制用例评审的开关。value值为'1'则开启，'0'则关闭。 */
$reviewData = new stdclass();
$reviewData->open = new stdclass();
$reviewData->open->forceReview = new stdclass();
$reviewData->open->forceReview->id     = zenData('setting')->dao->lastInsertID();
$reviewData->open->forceReview->owner  = 'system';
$reviewData->open->forceReview->module = 'testcase';
$reviewData->open->forceReview->key    = 'forceReview';

$reviewData->open->reviewCase = new stdClass();
$reviewData->open->reviewCase->id = zenData('setting')->dao->lastInsertID();
$reviewData->open->reviewCase->owner  = 'system';
$reviewData->open->reviewCase->module = 'testcase';
$reviewData->open->reviewCase->key    = 'reviewCase';
$reviewData->open->reviewCase->value  = '1';

$reviewData->open->needReview = new stdClass();
$reviewData->open->needReview->id = zenData('setting')->dao->lastInsertID();
$reviewData->open->needReview->owner  = 'system';
$reviewData->open->needReview->module = 'testcase';
$reviewData->open->needReview->key    = 'needReview';
$reviewData->open->needReview->value  = '1';

$reviewData->open->forceNotReview = new stdClass();
$reviewData->open->forceNotReview->id     = zenData('setting')->dao->lastInsertID();
$reviewData->open->forceNotReview->owner  = 'system';
$reviewData->open->forceNotReview->module = 'testcase';
$reviewData->open->forceNotReview->key    = 'forceNotReview';

zenData('setting')->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('testcase')->andWhere('key')->in('forceReview, reviewCase, needReview, forceNotReview')->exec();
zenData('setting')->dao->insert(TABLE_CONFIG)->data($reviewData->open->forceReview)->exec();
zenData('setting')->dao->insert(TABLE_CONFIG)->data($reviewData->open->reviewCase)->exec();
zenData('setting')->dao->insert(TABLE_CONFIG)->data($reviewData->open->needReview)->exec();
zenData('setting')->dao->insert(TABLE_CONFIG)->data($reviewData->open->forceNotReview)->exec();

$user = zenData('user');
$user->id->setFields(array(array('range' => zenData('user')->dao->lastInsertID())));
$user->account->setFields(array(array('range' => 'luxuyang')));
$user->realname->setFields(array(array('range' => '研发')));
$user->gen(1, $isClear = false);

zenData('case')->loadYaml('case')->gen(1);

$tester = new testcase();

$product  = array(
    'productID' => 1,
);
$testcase = array(
    'reviewedDate' => '2025-09-09',
    'result'       => '确认通过',
    'reviewedBy'   => array('admin', '研发'),
    'comment'      => '备注一下'
);

r($tester->testcaseReview($product, $testcase)) && p('message,status') && e('测试用例评审通过,SUCCESS'); //测试用例评审通过。

/* 还原zt_config、zt_user数据 */
zenData('setting')->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('testcase')->andWhere('key')->in('forceReview, reviewCase, needReview, forceNotReview')->exec();
zenData('user')->dao->delete()->from(TABLE_USER)->where('account')->eq('luxuyang')->exec();
$tester->closeBrowser();
