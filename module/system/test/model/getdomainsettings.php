#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$configData = zdTable('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('https,customDomain');
$configData->value->range('true,asd.ddd.sss');
$configData->gen(2);

/**

title=instanceModel->getDomainSettings();
timeout=0
cid=1

- 查看获取到的配置数量 @4
- 查看系统配置中的https属性https @true
- 查看系统配置中的customDomain属性customDomain @asd.ddd.sss

*/

global $tester;
$tester->loadModel('system');

$settings = $tester->system->getDomainSettings();

r(count((array)$settings)) && p('')             && e('4');    // 查看获取到的配置数量
r($settings)               && p('https')        && e('true'); // 查看系统配置中的https
r($settings)               && p('customDomain') && e('asd.ddd.sss'); // 查看系统配置中的customDomain