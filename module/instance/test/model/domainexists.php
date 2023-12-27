#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);
$configData = zdTable('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,dops.corp.cc');
$configData->gen(2);

/**

title=instanceModel->domainExists();
timeout=0
cid=1

- 查看该域名前缀是否存在 @0
- 查看该域名前缀是否存在 @1
- 查看该域名前缀是否存在 @1
- 查看该域名前缀是否存在 @1

*/

global $tester;
$tester->loadModel('instance');

r($tester->instance->domainExists('test')) && p('') && e('0'); // 查看该域名前缀是否存在
r($tester->instance->domainExists('rila')) && p('') && e('1'); // 查看该域名前缀是否存在
r($tester->instance->domainExists('7czx')) && p('') && e('1'); // 查看该域名前缀是否存在
r($tester->instance->domainExists('ane3')) && p('') && e('1'); // 查看该域名前缀是否存在