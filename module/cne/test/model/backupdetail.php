#!/usr/bin/env php
<?php

/**

title=测试 cneModel->backupDetail();
timeout=0
cid=1

- 空的数据 @0
- 错误的空间 @0
- 备份的数据库
 - 第0条的db_type属性 @mysql
 - 第0条的status属性 @completed
- 备份的数据
 - 第0条的volume属性 @data
 - 第0条的status属性 @completed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneModel  = new cneTest();

$instance = new stdClass();
r($cneModel->backupDetailTest($instance, 0)) && p() && e('0'); // 空的数据

$instance->spaceData = new stdClass();
$instance->spaceData->k8space = '';
r($cneModel->backupDetailTest($instance, 0)) && p() && e('0'); // 错误的空间

$instance->spaceData->k8space = 'quickon-system';
$instance->k8name = 'zentaopaas';

$backupDetail = $cneModel->backupDetailTest($instance, 1);
r($backupDetail->db)     && p('0:db_type,status') && e('mysql,completed'); // 备份的数据库
r($backupDetail->volume) && p('0:volume,status')  && e('data,completed');   // 备份的数据
