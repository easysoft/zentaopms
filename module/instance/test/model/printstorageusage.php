#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::printStorageUsage();
timeout=0
cid=16811

- 测试步骤1：10%使用率外部实例颜色属性color @secondary
- 测试步骤2：60%使用率外部实例颜色属性color @warning
- 测试步骤3：75%使用率外部实例颜色属性color @important
- 测试步骤4：90%使用率外部实例颜色属性color @danger
- 测试步骤5：用户来源实例返回值 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('instance')->gen(5);

su('admin');

$instanceTest = new instanceModelTest();

$userInstance = new stdClass();
$userInstance->source = 'user';
$userInstance->status = 'running';

$externalInstance = new stdClass();
$externalInstance->source = 'external';
$externalInstance->status = 'running';

$metrics10 = new stdClass();
$metrics10->rate = 10;
$metrics10->usage = 1024;
$metrics10->limit = 10240;

$metrics60 = new stdClass();
$metrics60->rate = 60;
$metrics60->usage = 6144;
$metrics60->limit = 10240;

$metrics75 = new stdClass();
$metrics75->rate = 75;
$metrics75->usage = 7680;
$metrics75->limit = 10240;

$metrics90 = new stdClass();
$metrics90->rate = 90;
$metrics90->usage = 9216;
$metrics90->limit = 10240;

r($instanceTest->printStorageUsageTest($externalInstance, $metrics10)) && p('color') && e('secondary'); // 测试步骤1：10%使用率外部实例颜色
r($instanceTest->printStorageUsageTest($externalInstance, $metrics60)) && p('color') && e('warning'); // 测试步骤2：60%使用率外部实例颜色
r($instanceTest->printStorageUsageTest($externalInstance, $metrics75)) && p('color') && e('important'); // 测试步骤3：75%使用率外部实例颜色
r($instanceTest->printStorageUsageTest($externalInstance, $metrics90)) && p('color') && e('danger'); // 测试步骤4：90%使用率外部实例颜色
r(count($instanceTest->printStorageUsageTest($userInstance, $metrics10))) && p() && e('5'); // 测试步骤5：用户来源实例返回值