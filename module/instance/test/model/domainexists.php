#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::domainExists();
timeout=0
cid=16791

- 执行instanceTest模块的domainExistsTest方法，参数是'existing'  @1
- 执行instanceTest模块的domainExistsTest方法，参数是'nonexistent'  @0
- 执行instanceTest模块的domainExistsTest方法，参数是''  @0
- 执行instanceTest模块的domainExistsTest方法，参数是'test-with-dash'  @0
- 执行instanceTest模块的domainExistsTest方法，参数是'deleted'  @0
- 执行instanceTest模块的domainExistsTest方法，参数是'another'  @1
- 执行instanceTest模块的domainExistsTest方法，参数是'very-long-domain-name-that-exceeds-normal-length'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->space->range('1{5}');
$instanceTable->name->range('App{5}');
$instanceTable->appID->range('1-5');
$instanceTable->appName->range('zentao{2},gitlab{2},jenkins{1}');
$instanceTable->chart->range('zentao{2},gitlab{2},jenkins{1}');
$instanceTable->status->range('running{3},stopped{2}');
$instanceTable->domain->range('existing.test.com,another.test.com,demo.test.com,deleted.test.com,removed.test.com');
$instanceTable->deleted->range('0{3},1{2}');
$instanceTable->gen(5);

$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('expiredDomain,customDomain');
$configData->value->range('`{"test":"test.com"}`,test.com');
$configData->gen(2);

su('admin');

$instanceTest = new instanceModelTest();

r($instanceTest->domainExistsTest('existing')) && p('') && e('1');
r($instanceTest->domainExistsTest('nonexistent')) && p('') && e('0');
r($instanceTest->domainExistsTest('')) && p('') && e('0');
r($instanceTest->domainExistsTest('test-with-dash')) && p('') && e('0');
r($instanceTest->domainExistsTest('deleted')) && p('') && e('0');
r($instanceTest->domainExistsTest('another')) && p('') && e('1');
r($instanceTest->domainExistsTest('very-long-domain-name-that-exceeds-normal-length')) && p('') && e('0');