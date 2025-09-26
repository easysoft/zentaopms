#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backup();
timeout=0
cid=0

- 步骤1：正常实例备份，使用默认用户账号属性code @200
- 步骤2：正常实例备份，指定用户账号属性code @200
- 步骤3：正常实例备份，指定备份模式manual属性code @200
- 步骤4：正常实例备份，指定备份模式system属性code @200
- 步骤5：正常实例备份，指定备份模式upgrade属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 准备测试数据
$space = zenData('space');
$space->id->range('1-10');
$space->k8space->range('quickon{3},app{3},test{4}');
$space->owner->range('admin{5},user1{3},user2{2}');
$space->name->range('默认空间{5},测试空间{3},应用空间{2}');
$space->gen(10);

$instance = zenData('instance');
$instance->id->range('1-10');
$instance->space->range('1-10');
$instance->name->range('禅道开源版{3},禅道企业版{3},禅道旗舰版{2},Subversion{2}');
$instance->appVersion->range('10.0.1{3},4.8.1{3},8.9{2},15.5{2}');
$instance->chart->range('zentao{6},zentao-biz{2},zentao-max{2}');
$instance->version->range('2023.12.1201{4},2023.12.1202{3},2023.12.1203{3}');
$instance->source->range('cloud');
$instance->channel->range('stable{8},beta{2}');
$instance->status->range('running{8},stopped{1},abnormal{1}');
$instance->domain->range('rila.dops.corp.cc{2},7czx.dops.corp.cc{2},xqly.dops.corp.cc{2},ane3.dops.corp.cc{2},qphl.dops.corp.cc{2}');
$instance->gen(10);

$cneTest = new cneTest();

// 测试步骤
r($cneTest->backupTest(1)) && p('code') && e('200'); // 步骤1：正常实例备份，使用默认用户账号
r($cneTest->backupTest(1, 'testuser')) && p('code') && e('200'); // 步骤2：正常实例备份，指定用户账号
r($cneTest->backupTest(1, null, 'manual')) && p('code') && e('200'); // 步骤3：正常实例备份，指定备份模式manual
r($cneTest->backupTest(2, 'admin', 'system')) && p('code') && e('200'); // 步骤4：正常实例备份，指定备份模式system
r($cneTest->backupTest(2, null, 'upgrade')) && p('code') && e('200'); // 步骤5：正常实例备份，指定备份模式upgrade