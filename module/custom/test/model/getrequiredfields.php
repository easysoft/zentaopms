#!/usr/bin/env php
<?php

/**

title=测试 customModel::getRequiredFields();
cid=0

- 测试空配置对象处理 >> 期望返回空数组
- 测试单个方法配置获取 >> 期望正确提取必填字段
- 测试多个方法配置处理 >> 期望处理多个配置方法
- 测试空格字符处理 >> 期望去除空格和换行符
- 测试混合配置过滤 >> 期望只返回包含必填字段的配置
- 测试非对象配置跳过 >> 期望跳过非对象类型配置
- 测试复杂字段配置 >> 期望处理复杂必填字段列表

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

su('admin');

// 测试数据1：空配置对象
$emptyConfig = new stdclass();

// 测试数据2：单个方法配置
$taskConfig = new stdclass();
$taskConfig->create = new stdclass();
$taskConfig->create->requiredFields = 'name,begin,end';

// 测试数据3：需求配置
$storyConfig = new stdclass();
$storyConfig->edit = new stdclass();
$storyConfig->edit->requiredFields = 'title';

// 测试数据4：执行配置
$executionConfig = new stdclass();
$executionConfig->batchedit = new stdclass();
$executionConfig->batchedit->requiredFields = 'name,code,begin,end';

// 测试数据5：包含空格的必填字段
$spaceConfig = new stdclass();
$spaceConfig->create = new stdclass();
$spaceConfig->create->requiredFields = ' name , type , status ';

// 测试数据6：混合配置（有些有requiredFields，有些没有）
$mixedConfig = new stdclass();
$mixedConfig->create = new stdclass();
$mixedConfig->create->requiredFields = 'name,status';
$mixedConfig->edit = new stdclass();
$mixedConfig->edit->otherField = 'value';
$mixedConfig->delete = new stdclass();
$mixedConfig->delete->requiredFields = 'id';

// 测试数据7：非对象子配置
$invalidConfig = new stdclass();
$invalidConfig->stringMethod = 'invalid';
$invalidConfig->arrayMethod = array('invalid');
$invalidConfig->validMethod = new stdclass();
$invalidConfig->validMethod->requiredFields = 'field1,field2';

// 测试数据8：复杂必填字段配置
$complexConfig = new stdclass();
$complexConfig->batchcreate = new stdclass();
$complexConfig->batchcreate->requiredFields = 'name,type,pri,estimate,assignedTo,deadline';

$customTester = new customTest();
r($customTester->getRequiredFieldsTest($emptyConfig))     && p()                 && e('0');                                              // 测试步骤1：空配置对象
r($customTester->getRequiredFieldsTest($taskConfig))      && p('create', ';')    && e('name,begin,end');                              // 测试步骤2：单个方法配置
r($customTester->getRequiredFieldsTest($storyConfig))     && p('edit', ';')      && e('title');                                       // 测试步骤3：需求配置方法
r($customTester->getRequiredFieldsTest($executionConfig)) && p('batchedit', ';') && e('name,code,begin,end');                        // 测试步骤4：执行配置方法
r($customTester->getRequiredFieldsTest($spaceConfig))     && p('create', ';')    && e('name,type,status');                           // 测试步骤5：空格处理
r($customTester->getRequiredFieldsTest($mixedConfig))     && p('create,delete')  && e('name,status~~id');                            // 测试步骤6：混合配置
r($customTester->getRequiredFieldsTest($invalidConfig))   && p('validMethod', ';') && e('field1,field2');                           // 测试步骤7：非对象配置
r($customTester->getRequiredFieldsTest($complexConfig))   && p('batchcreate', ';') && e('name,type,pri,estimate,assignedTo,deadline'); // 测试步骤8：复杂字段配置
