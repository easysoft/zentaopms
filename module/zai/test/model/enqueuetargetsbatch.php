#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->enqueueTargetsBatch();
timeout=0
cid=19811

- 测试未启用时返回 fail @fail
- 测试启用后按游标入队数量 @1
- 测试入队后游标更新 @1
- 测试入队未完成标记 @unfinished
- 测试全部入队完成后 finished @finished

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('config')->gen(0);
zenData('story')->gen(3);
zenData('bug')->gen(2);

su('admin');

global $tester;
$zai = new zaiModelTest();

$tester->dbh->exec("CREATE TABLE IF NOT EXISTS `zt_ai_vectorqueue` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` int unsigned NOT NULL DEFAULT 0,
  `retries` tinyint unsigned NOT NULL DEFAULT 0,
  `lastError` text NULL,
  `lastSyncTime` datetime DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_object` (`objectType`, `objectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$tester->dbh->exec("DELETE FROM `zt_ai_vectorqueue`");

$disabled = $zai->enqueueTargetsBatchTest();
r($disabled['result']) && p() && e('fail'); // 测试未启用时返回 fail

$vectorInfo = new stdClass();
$vectorInfo->key             = 'memory-key-2';
$vectorInfo->status          = 'syncing';
$vectorInfo->syncedTime      = 0;
$vectorInfo->syncedCount     = 0;
$vectorInfo->syncFailedCount = 0;
$vectorInfo->syncTime        = 0;
$vectorInfo->syncingType     = 'story';
$vectorInfo->syncingID       = 0;
$vectorInfo->syncDetails     = new stdClass();
$vectorInfo->enqueueMaxIDs   = new stdClass();
$vectorInfo->enqueueMaxIDs->story = 0;
$vectorInfo->createdAt       = time();
$vectorInfo->createdBy       = 'admin';
$zai->setVectorizedInfoTest($vectorInfo);

$tester->config->zai->vectorEnqueueLimit = 1;
$result = $zai->enqueueTargetsBatchTest();
r((string)$result['count']) && p() && e('1'); // 测试启用后按游标入队数量

$info = $zai->getVectorizedInfoTest();
r((int)$info->enqueueMaxIDs->story) && p() && e('1'); // 测试入队后游标更新
r($result['result']) && p() && e('unfinished'); // 测试入队未完成标记

$batch = array('result' => 'unfinished');
for($i = 0; $i < 20; $i++)
{
    $batch = $zai->enqueueTargetsBatchTest();
    if(!empty($batch['finished'])) break;
}
r($batch['result']) && p() && e('finished'); // 测试全部入队完成后 finished
