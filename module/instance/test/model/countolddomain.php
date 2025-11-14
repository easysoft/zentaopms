#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::countOldDomain();
timeout=0
cid=16785

- 执行instanceTest模块的countOldDomainTest方法  @6
- 执行instanceTest模块的countOldDomainTest方法  @0
- 执行instanceTest模块的countOldDomainTest方法  @4
- 执行instanceTest模块的countOldDomainTest方法  @8
- 执行instanceTest模块的countOldDomainTest方法  @0
- 执行instanceTest模块的countOldDomainTest方法  @4
- 执行instanceTest模块的countOldDomainTest方法  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

su('admin');

$instanceTest = new instanceTest();

// 准备基础配置数据，确保cne模块的sysDomain方法可以正常工作
zenData('config')->gen(0);
$configTable = zenData('config');
$configTable->owner->range('system');
$configTable->module->range('cne');
$configTable->section->range('');
$configTable->key->range('domain');
$configTable->value->range('zentao.com');
$configTable->gen(1);

// 测试1：包含多层子域名的旧域名实例计数
zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-8');
$table->domain->range('app1.old.zentao.com{2}, app2.sub.zentao.com{2}, deep.nested.sub.zentao.com{1}, very.deep.multi.level.zentao.com{1}, simple{2}');
$table->deleted->range('0{8}');
$table->gen(8);

r($instanceTest->countOldDomainTest()) && p() && e('6');

// 测试2：仅包含简单域名的实例计数（无旧域名）
zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-5');
$table->domain->range('simple{3}, basic{2}');
$table->deleted->range('0{5}');
$table->gen(5);

r($instanceTest->countOldDomainTest()) && p() && e('0');

// 测试3：包含已删除实例的旧域名计数过滤
zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-6');
$table->domain->range('app1.old.zentao.com{2}, app2.sub.zentao.com{2}, deleted.old.zentao.com{2}');
$table->deleted->range('0{4}, 1{2}');
$table->gen(6);

r($instanceTest->countOldDomainTest()) && p() && e('4');

// 测试4：混合域名格式的复杂场景计数
zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-10');
$table->domain->range('app1.old.zentao.com{1}, simple{2}, nested.sub.app.zentao.com{2}, test.zentao.com{1}, prod.api.zentao.com{1}, admin.zentao.com{1}, user.app.zentao.com{2}');
$table->deleted->range('0{10}');
$table->gen(10);

r($instanceTest->countOldDomainTest()) && p() && e('8');

// 测试5：空数据库场景的边界值处理
zenData('instance')->gen(0);

r($instanceTest->countOldDomainTest()) && p() && e('0');

// 测试6：系统域名配置异常的容错处理
zenData('config')->gen(0);
zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-4');
$table->domain->range('app1.old.example.com{2}, app2.sub.example.com{2}');
$table->deleted->range('0{4}');
$table->gen(4);

r($instanceTest->countOldDomainTest()) && p() && e('4');

// 测试7：极端长域名和特殊字符域名处理
zenData('config')->gen(0);
$configTable = zenData('config');
$configTable->owner->range('system');
$configTable->module->range('cne');
$configTable->section->range('');
$configTable->key->range('domain');
$configTable->value->range('test-domain.com');
$configTable->gen(1);

zenData('instance')->gen(0);
$table = zenData('instance');
$table->id->range('1-6');
$table->domain->range('very-long-subdomain-name-with-dashes.test-domain.com{1}, app.test-domain.com{1}, simple-name{1}, sub.nested.test-domain.com{2}, special-chars.test-domain.com{1}');
$table->deleted->range('0{6}');
$table->gen(6);

r($instanceTest->countOldDomainTest()) && p() && e('5');