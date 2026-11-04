#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableRelationConfig();
timeout=0
cid=0

- 步骤1：任务模块存在关联配置 @yes
- 步骤2：任务模块 project 字段关联 project 表 @project
- 步骤3：任务模块 project 字段关联 id 列 @id
- 步骤4：附件模块 objectID 字段是多态关联 @polymorphic
- 步骤5：不存在的模块返回空 @0
- 步骤6：任务模块 parent 字段存在关联配置 @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

r(count($dev->getTableRelationConfigTest('task')) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：任务模块存在关联配置
r($dev->getTableRelationConfigTest('task')['project']['toTable']) && p() && e('project');  // 步骤2：任务模块 project 字段关联 project 表
r($dev->getTableRelationConfigTest('task')['project']['toColumn']) && p() && e('id');      // 步骤3：任务模块 project 字段关联 id 列
r($dev->getTableRelationConfigTest('file')['objectID']['type']) && p() && e('polymorphic'); // 步骤4：附件模块 objectID 字段是多态关联
r($dev->getTableRelationConfigTest('no_such_module')) && p() && e('0');                    // 步骤5：不存在的模块返回空
r(isset($dev->getTableRelationConfigTest('task')['parent']) ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：任务模块 parent 字段存在关联配置
