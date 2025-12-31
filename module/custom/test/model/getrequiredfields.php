#!/usr/bin/env php
<?php

/**

title=测试 customModel::getRequiredFields();
timeout=0
cid=15901

- 测试步骤1：空配置对象 @0
- 测试步骤2：单个方法配置属性create @name
- 测试步骤3：需求配置方法属性edit @title
- 测试步骤4：执行配置方法属性batchedit @name,code,begin,end
- 测试步骤5：空格处理属性create @name,type,status

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

su('admin');

// 测试数据1：空配置对象
$emptyConfig = new stdclass();

// 测试数据2：单个方法配置
$taskConfig = new stdclass();
$taskConfig->create = new stdclass();
$taskConfig->create->requiredFields = 'name';

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
r($customTester->getRequiredFieldsTest($emptyConfig))     && p()                 && e('0');                   // 测试步骤1：空配置对象
r($customTester->getRequiredFieldsTest($taskConfig))      && p('create', ';')    && e('name');                // 测试步骤2：单个方法配置
r($customTester->getRequiredFieldsTest($storyConfig))     && p('edit', ';')      && e('title');               // 测试步骤3：需求配置方法
r($customTester->getRequiredFieldsTest($executionConfig)) && p('batchedit', ';') && e('name,code,begin,end'); // 测试步骤4：执行配置方法
r($customTester->getRequiredFieldsTest($spaceConfig))     && p('create', ';')    && e('name,type,status');    // 测试步骤5：空格处理
