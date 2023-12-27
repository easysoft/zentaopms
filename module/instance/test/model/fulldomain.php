#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$configData = zdTable('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,dops.corp.cc');
$configData->gen(2);

/**

title=instanceModel->fullDomain();
timeout=0
cid=1

- 查看完整域名 @test.dops.corp.cc
- 查看完整域名 @rila.dops.corp.cc
- 查看完整域名 @7czx.dops.corp.cc
- 查看完整域名 @ane3.dops.corp.cc

*/

global $tester;
$tester->loadModel('instance');

r($tester->instance->fullDomain('test')) && p('') && e('test.dops.corp.cc'); // 查看完整域名
r($tester->instance->fullDomain('rila')) && p('') && e('rila.dops.corp.cc'); // 查看完整域名
r($tester->instance->fullDomain('7czx')) && p('') && e('7czx.dops.corp.cc'); // 查看完整域名
r($tester->instance->fullDomain('ane3')) && p('') && e('ane3.dops.corp.cc'); // 查看完整域名