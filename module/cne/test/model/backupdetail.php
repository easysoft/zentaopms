#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backupDetail();
timeout=0
cid=15604

- 测试步骤1：空实例对象输入 @0
- 测试步骤2：缺少k8name属性的实例 @0
- 测试步骤3：无效备份计数输入 @0
- 测试步骤4：正常实例获取备份详情-数据库
 - 第0条的db_type属性 @mysql
 - 第0条的status属性 @completed
- 测试步骤5：正常实例获取备份详情-卷
 - 第0条的volume属性 @data
 - 第0条的status属性 @completed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$cneTest = new cneModelTest();

r($cneTest->backupDetailTest(new stdClass(), 0)) && p() && e('0'); // 测试步骤1：空实例对象输入
$instance = new stdClass(); $instance->spaceData = new stdClass(); $instance->spaceData->k8space = '';
r($cneTest->backupDetailTest($instance, 0)) && p() && e('0'); // 测试步骤2：缺少k8name属性的实例
$instance->k8name = 'zentaopaas'; $instance->spaceData->k8space = 'quickon-system';
r($cneTest->backupDetailTest($instance, 0)) && p() && e('0'); // 测试步骤3：无效备份计数输入
$backupDetail = $cneTest->backupDetailTest($instance, 1);
r($backupDetail->db) && p('0:db_type,status') && e('mysql,completed'); // 测试步骤4：正常实例获取备份详情-数据库
r($backupDetail->volume) && p('0:volume,status') && e('data,completed'); // 测试步骤5：正常实例获取备份详情-卷