#!/usr/bin/env php
<?php

/**

title=测试 apiModel::updateStruct();
timeout=0
cid=15123

- 执行apiTest模块的updateStructTest方法  @1
- 执行apiTest模块的updateStructTest方法 第name条的0属性 @『结构名』不能为空。
- 执行apiTest模块的updateStructTest方法  @1
- 执行apiTest模块的updateStructTest方法  @1
- 执行apiTest模块的updateStructTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('apistruct');
$table->id->range('1-10');
$table->lib->range('1');
$table->name->range('数据结构{1-10}');
$table->type->range('object,array');
$table->desc->range('数据结构描述{1-10}');
$table->version->range('1');
$table->attribute->range('{"field1":"string","field2":"int"}');
$table->addedBy->range('admin');
$table->addedDate->range('`2023-01-01 10:00:00`');
$table->editedBy->range('admin');
$table->editedDate->range('`2023-01-01 10:00:00`');
$table->deleted->range('0');
$table->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('API文档库{1-5}');
$doclib->type->range('api');
$doclib->gen(3);

su('admin');

$apiTest = new apiModelTest();

r($apiTest->updateStructTest((object)array('id' => 1, 'lib' => 1, 'name' => '更新后的数据结构', 'type' => 'object', 'desc' => '更新后的描述', 'attribute' => '{"field1":"string","field2":"number"}'))) && p() && e(1);

r($apiTest->updateStructTest((object)array('id' => 2, 'lib' => 1, 'name' => '', 'type' => 'object', 'desc' => '测试空名称'))) && p('name:0') && e('『结构名』不能为空。');

r($apiTest->updateStructTest((object)array('id' => 999, 'lib' => 1, 'name' => '不存在的结构', 'type' => 'object', 'desc' => '测试不存在ID'))) && p() && e(1);

r($apiTest->updateStructTest((object)array('id' => 3, 'lib' => 1, 'name' => 'JSON测试结构', 'type' => 'array', 'desc' => 'JSON属性测试', 'attribute' => '{"items":{"type":"object","properties":{"id":"integer","name":"string"}}}'))) && p() && e(1);

r($apiTest->updateStructTest((object)array('id' => 4, 'lib' => 1, 'name' => '历史记录测试结构', 'type' => 'object', 'desc' => '测试变更历史记录生成', 'attribute' => '{"newField":"boolean"}'))) && p() && e(1);