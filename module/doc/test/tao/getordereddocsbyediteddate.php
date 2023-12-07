#!/usr/bin/env php
<?php
/**

title=测试 docModel->getOrderedDocsByEditedDate();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doclib')->config('doclib')->gen(30);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
su('admin');

$hasPrivDocIdList[0] = array();
$hasPrivDocIdList[1] = range(1, 30);
$hasPrivDocIdList[2] = range(41, 60);
$hasPrivDocIdList[3] = range(51, 60);

$allLibIDList[0] = array();
$allLibIDList[1] = range(1, 20);
$allLibIDList[2] = range(21, 40);
$allLibIDList[3] = range(31, 40);

$docTester = new docTest();
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[0], $allLibIDList[0])) && p()           && e('0');              // 获取没有可查看文档、没有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[0], $allLibIDList[1])) && p()           && e('0');              // 获取没有可查看文档、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[0], $allLibIDList[2])) && p()           && e('0');              // 获取没有可查看文档、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[0], $allLibIDList[3])) && p()           && e('0');              // 获取没有可查看文档、有可查看文档库但数据不存在、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[1], $allLibIDList[0])) && p()           && e('0');              // 获取有可查看文档、没有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[1], $allLibIDList[1])) && p('30:title') && e('执行草稿文档30'); // 获取有可查看文档、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[1], $allLibIDList[2])) && p()           && e('0');              // 获取有可查看文档、有可查看文档库但库下文档不在可查看范围里、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[1], $allLibIDList[3])) && p()           && e('0');              // 获取有可查看文档、有可查看文档库但数据不存在、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[2], $allLibIDList[0])) && p()           && e('0');              // 获取有可查看文档、没有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[2], $allLibIDList[1])) && p()           && e('0');              // 获取有可查看文档、有可查看文档库但库下文档不在可查看范围里、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[2], $allLibIDList[2])) && p('50:title') && e('产品草稿文档50'); // 获取有可查看文档、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[2], $allLibIDList[3])) && p()           && e('0');              // 获取有可查看文档、有可查看文档库但数据不存在、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[3], $allLibIDList[0])) && p()           && e('0');              // 获取有可查看文档但数据不存在、没有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[3], $allLibIDList[1])) && p()           && e('0');              // 获取有可查看文档但数据不存在、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[3], $allLibIDList[2])) && p()           && e('0');              // 获取有可查看文档但数据不存在、有可查看文档库、按照编辑日期倒序排列的文档列表
r($docTester->getOrderedDocsByEditedDateTest($hasPrivDocIdList[3], $allLibIDList[3])) && p()           && e('0');              // 获取有可查看文档但数据不存在、有可查看文档库但数据不存在、按照编辑日期倒序排列的文档列表
