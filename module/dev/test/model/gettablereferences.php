#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableReferences();
timeout=0
cid=0

- 步骤1：任务模块 project 字段关联的表 @zt_project
- 步骤2：任务模块 project 字段关联的列 @id
- 步骤3：附件模块 objectID 字段是多态关联 @polymorphic
- 步骤4：附件模块多态关联的目标表 @zt_task
- 步骤5：不存在的模块返回 missing @missing
- 步骤6：模块名为空时返回空 @0

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

r($dev->getTableReferenceTest('task', 'project', 'toTable')) && p() && e('zt_project');   // 步骤1：任务模块 project 字段关联的表
r($dev->getTableReferenceTest('task', 'project', 'toColumn')) && p() && e('id');         // 步骤2：任务模块 project 字段关联的列
r($dev->getTableReferenceTest('file', 'objectID', 'type')) && p() && e('polymorphic');   // 步骤3：附件模块 objectID 字段是多态关联
r($dev->getTableReferenceTest('file', 'objectID', 'targets.1.toTable')) && p() && e('zt_task'); // 步骤4：附件模块多态关联的目标表
r($dev->getTableReferenceTest('no_such_module', 'any')) && p() && e('missing');           // 步骤5：不存在的模块返回 missing
r($dev->getTableReferencesTest('')) && p() && e('0');                                    // 步骤6：模块名为空时返回空
