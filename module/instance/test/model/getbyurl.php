#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::getByUrl();
timeout=0
cid=16801

- 执行instanceTest模块的getByUrlTest方法，参数是'rila.dops.corp.cc' 属性id @1
- 执行instanceTest模块的getByUrlTest方法，参数是'https://7czx.dops.corp.cc' 属性id @2
- 执行instanceTest模块的getByUrlTest方法，参数是'http://test.example.com' 属性id @3
- 执行instanceTest模块的getByUrlTest方法，参数是'nonexistent.domain.com'  @0
- 执行instanceTest模块的getByUrlTest方法，参数是''  @0
- 执行instanceTest模块的getByUrlTest方法，参数是'special-chars.com' 属性id @8
- 执行instanceTest模块的getByUrlTest方法，参数是'long-domain-name-test.example.org' 属性id @9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('instance');
$table->id->range('1-10');
$table->domain->range('rila.dops.corp.cc,7czx.dops.corp.cc,test.example.com,app.zentao.net,demo.gitlab.com,ci.jenkins.org,empty.domain.test,special-chars.com,long-domain-name-test.example.org,localhost');
$table->name->range('instance{1-10}');
$table->status->range('running');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$instanceTest = new instanceModelTest();

r($instanceTest->getByUrlTest('rila.dops.corp.cc')) && p('id') && e('1');
r($instanceTest->getByUrlTest('https://7czx.dops.corp.cc')) && p('id') && e('2');
r($instanceTest->getByUrlTest('http://test.example.com')) && p('id') && e('3');
r($instanceTest->getByUrlTest('nonexistent.domain.com')) && p() && e('0');
r($instanceTest->getByUrlTest('')) && p() && e('0');
r($instanceTest->getByUrlTest('special-chars.com')) && p('id') && e('8');
r($instanceTest->getByUrlTest('long-domain-name-test.example.org')) && p('id') && e('9');