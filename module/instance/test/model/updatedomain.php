#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(5);
zdTable('space')->gen(5);

$configData = zdTable('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,asd.ddd.sss');
$configData->gen(2);

/**

title=instanceModel->updateDomain();
timeout=0
cid=1

- 查看编辑之前的应用域名属性domain @rila.dops.corp.cc
- 编辑应用的域名，返回true，但是因为测试域名ping不同，所以不会更新域名 @1
- 查看编辑之后的应用域名属性domain @rila.dops.corp.cc

*/

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByID(1);

r($instance) && p('domain') && e('rila.dops.corp.cc'); // 查看编辑之前的应用域名
r($tester->instance->updateDomain($instance)) && p('') && e('1'); // 编辑应用的域名，返回true，但是因为测试域名ping不同，所以不会更新域名
r($instance) && p('domain') && e('rila.dops.corp.cc'); // 查看编辑之后的应用域名