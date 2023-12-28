#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('instance')->gen(1);

$configData = zdTable('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"dops.corp.cc"}`,asd.ddd.sss');
$configData->gen(2);

/**

title=instanceModel->saveDomainSettings();
timeout=0
cid=1

- 查看实例的domain属性属性domain @rila.dops.corp.cc
- 修改前后域名相同，报错 @新域名不能与原域名相同
- cne没有修改成功，则实例的domain属性不变属性domain @rila.dops.corp.cc
- 没有填写公钥证书，报错第certPem条的0属性 @『公钥证书』不能为空。
- 没有填写私钥，报错第certKey条的0属性 @『私钥』不能为空。
- 没有填写新域名，报错第customDomain条的0属性 @『新域名』不能为空。

*/

global $tester;
$tester->loadModel('system');

$settings = new stdClass();
$settings->customDomain = 'asd.ddd.sss';
$settings->certPem      = '';
$settings->certKey      = '';
$settings->https        = 'false';

$instance = $tester->loadModel('instance')->getById(1);
r($instance) && p('domain') && e('rila.dops.corp.cc'); // 查看实例的domain属性

$tester->system->saveDomainSettings($settings);
r(dao::getError()) && p('0') && e('新域名不能与原域名相同'); // 修改前后域名相同，报错

$settings->customDomain = 'test.ddd.sss';
$tester->system->saveDomainSettings($settings);
$instance = $tester->loadModel('instance')->getById(1);
r($instance) && p('domain') && e('rila.dops.corp.cc'); // cne没有修改成功，则实例的domain属性不变

$settings->https = 'true';
$tester->system->saveDomainSettings($settings);
$errors = dao::getError();
r($errors) && p('certPem:0') && e('『公钥证书』不能为空。'); // 没有填写公钥证书，报错
r($errors) && p('certKey:0') && e('『私钥』不能为空。'); // 没有填写私钥，报错

$settings->customDomain = '';
$tester->system->saveDomainSettings($settings);
r(dao::getError()) && p('customDomain:0') && e('『新域名』不能为空。'); // 没有填写新域名，报错