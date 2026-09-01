#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->pushToVectorQueue();
timeout=0
cid=19801

- 测试向量化未启用时入队 @0
- 测试删除动作忽略入队 @0
- 测试启用后入队成功 @1
- 测试同对象重复入队数量仍为1 @1
- 测试非法类型入队失败 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
su('admin');

global $tester;
$zai = new zaiModelTest();

/* Ensure queue table exists for unit test DB. */
$tester->dbh->exec("CREATE TABLE IF NOT EXISTS `zt_zaivectorqueue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` int unsigned NOT NULL DEFAULT 0,
  `retries` tinyint unsigned NOT NULL DEFAULT 0,
  `lastError` text NULL,
  `lastSyncTime` datetime DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`objectType`, `objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$tester->dbh->exec("DELETE FROM `zt_zaivectorqueue`");

r($zai->pushToVectorQueueTest('story', 1, 'edited')) && p() && e('0'); // 测试向量化未启用时入队
r($zai->pushToVectorQueueTest('story', 1, 'deleted')) && p() && e('0'); // 测试删除动作忽略入队

$vectorInfo = new stdClass();
$vectorInfo->key = 'memory-key-1';
$vectorInfo->status = 'syncing';
$vectorInfo->syncedTime = 0;
$vectorInfo->syncedCount = 0;
$vectorInfo->syncFailedCount = 0;
$vectorInfo->syncTime = 0;
$vectorInfo->syncingType = 'story';
$vectorInfo->syncingID = 0;
$vectorInfo->syncDetails = new stdClass();
$vectorInfo->enqueueMaxIDs = new stdClass();
$vectorInfo->createdAt = time();
$vectorInfo->createdBy = 'admin';
$zai->setVectorizedInfoTest($vectorInfo);

r($zai->pushToVectorQueueTest('story', 1, 'edited')) && p() && e('1'); // 测试启用后入队成功
$zai->pushToVectorQueueTest('story', 1, 'edited');
$count = $tester->dao->select('COUNT(1) AS count')->from(TABLE_AI_VECTORQUEUE)->where('objectType')->eq('story')->andWhere('objectID')->eq(1)->fetch('count');
r((int)$count) && p() && e('1'); // 测试同对象重复入队数量仍为1

r($zai->pushToVectorQueueTest('task', 1, 'edited')) && p() && e('0'); // 测试非法类型入队失败
