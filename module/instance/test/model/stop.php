#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::stop();
timeout=0
cid=0

- 测试步骤1:成功停止实例属性code @200
- 测试步骤2:API调用失败属性code @400
- 测试步骤3:服务器错误属性code @500
- 测试步骤4:资源不存在属性code @404
- 测试步骤5:默认情况属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

$space = zenData('space');
$space->id->range('1-5');
$space->name->range('Space1,Space2,Space3,Space4,Space5');
$space->k8space->range('k8space1,k8space2,k8space3,k8space4,k8space5');
$space->owner->range('admin,user1,user2,admin,user3');
$space->deleted->range('0');
$space->gen(5);

$instance = zenData('instance');
$instance->id->range('1-5');
$instance->space->range('1-5');
$instance->name->range('Instance1,Instance2,Instance3,Instance4,Instance5');
$instance->k8name->range('k8name1,k8name2,k8name3,k8name4,k8name5');
$instance->chart->range('zentao,gitlab,gitea,jenkins,sonarqube');
$instance->channel->range('stable{5}');
$instance->status->range('running,abnormal,stopped,creating,destroying');
$instance->deleted->range('0');
$instance->gen(5);

su('admin');

$instanceTest = new instanceTest();

$testInstance1 = new stdclass();
$testInstance1->id = 1;
$testInstance1->k8name = 'k8name1';
$testInstance1->chart = 'zentao';
$testInstance1->channel = 'stable';
$testInstance1->spaceData = new stdclass();
$testInstance1->spaceData->k8space = 'k8space1';

$testInstance2 = new stdclass();
$testInstance2->id = 2;
$testInstance2->k8name = 'k8name2';
$testInstance2->chart = 'gitlab';
$testInstance2->channel = 'stable';
$testInstance2->spaceData = new stdclass();
$testInstance2->spaceData->k8space = 'k8space2';

$testInstance3 = new stdclass();
$testInstance3->id = 3;
$testInstance3->k8name = 'k8name3';
$testInstance3->chart = 'gitea';
$testInstance3->channel = 'stable';
$testInstance3->spaceData = new stdclass();
$testInstance3->spaceData->k8space = 'k8space3';

$testInstance4 = new stdclass();
$testInstance4->id = 4;
$testInstance4->k8name = 'k8name4';
$testInstance4->chart = 'jenkins';
$testInstance4->channel = 'stable';
$testInstance4->spaceData = new stdclass();
$testInstance4->spaceData->k8space = 'k8space4';

$testInstance5 = new stdclass();
$testInstance5->id = 5;
$testInstance5->k8name = 'k8name5';
$testInstance5->chart = 'sonarqube';
$testInstance5->channel = 'stable';
$testInstance5->spaceData = new stdclass();
$testInstance5->spaceData->k8space = 'k8space5';

r($instanceTest->stopTest($testInstance1)) && p('code') && e('200'); // 测试步骤1:成功停止实例
r($instanceTest->stopTest($testInstance2)) && p('code') && e('400'); // 测试步骤2:API调用失败
r($instanceTest->stopTest($testInstance3)) && p('code') && e('500'); // 测试步骤3:服务器错误
r($instanceTest->stopTest($testInstance4)) && p('code') && e('404'); // 测试步骤4:资源不存在
r($instanceTest->stopTest($testInstance5)) && p('code') && e('200'); // 测试步骤5:默认情况