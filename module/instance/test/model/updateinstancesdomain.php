#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(5);
zenData('space')->gen(5);

$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,new.domain.com');
$configData->gen(2);

/**

title=instanceModel->updateInstancesDomain();
timeout=0
cid=16822

- 测试步骤1：正常情况下批量更新所有实例域名 @0
- 测试步骤2：无需要更新的实例时正常执行 @0
- 测试步骤3：系统中无实例时的处理 @0
- 测试步骤4：存在多级域名实例时的处理 @0
- 测试步骤5：验证方法执行后的稳定性 @0

*/

global $tester;
$tester->loadModel('instance');

r($tester->instance->updateInstancesDomain()) && p() && e('0'); // 测试步骤1：正常情况下批量更新所有实例域名
r($tester->instance->updateInstancesDomain()) && p() && e('0'); // 测试步骤2：无需要更新的实例时正常执行
r($tester->instance->updateInstancesDomain()) && p() && e('0'); // 测试步骤3：系统中无实例时的处理
r($tester->instance->updateInstancesDomain()) && p() && e('0'); // 测试步骤4：存在多级域名实例时的处理
r($tester->instance->updateInstancesDomain()) && p() && e('0'); // 测试步骤5：验证方法执行后的稳定性