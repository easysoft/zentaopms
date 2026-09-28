#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDbTable();
timeout=0
cid=16020

- 步骤1：任务表的表名 @zt_task
- 步骤2：任务表的主键 @id
- 步骤3：字段关联的目标表 @zt_project
- 步骤4：字段关联的目标字段 @id
- 步骤5：字段类型为 int @int
- 步骤6：字段描述来自语言文件 @yes
- 步骤7：入向关联由配置反推 @zt_task,parent
- 步骤8：多态关联的类型 @polymorphic
- 步骤9：多态区分字段的取值包含 bug @yes
- 步骤10：枚举字段的取值 @enum
- 步骤11：不存在的表返回空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

$dev = new devModelTest();

r($dev->getDbTableTest('zt_task'))  && p('name')       && e('zt_task');  // 步骤1：任务表的表名
r($dev->getDbTableTest('zt_task'))  && p('primaryKey') && e('id');       // 步骤2：任务表的主键

r($dev->getDbTableColumnTest('zt_task', 'project', 'references.toTable'))  && p() && e('zt_project'); // 步骤3：字段关联的目标表
r($dev->getDbTableColumnTest('zt_task', 'project', 'references.toColumn')) && p() && e('id');         // 步骤4：字段关联的目标字段
r($dev->getDbTableColumnTest('zt_task', 'project', 'type'))                && p() && e('int');        // 步骤5：字段类型为 int
$projectDesc = $dev->getDbTableColumnTest('zt_task', 'project', 'description');
r(strpos($projectDesc, '所属项目') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：字段描述来自语言文件

r($dev->getDbTableTest('zt_task')) && p('referencedBy[0]:table,column') && e('zt_task,parent'); // 步骤7：入向关联由配置反推

r($dev->getDbTableColumnTest('zt_file', 'objectID', 'references.type')) && p() && e('polymorphic'); // 步骤8：多态关联的类型

$objectTypeOptions = $dev->getDbTableColumnTest('zt_file', 'objectType', 'options');
r(strpos($objectTypeOptions, '"bug"') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤9：多态区分字段的取值包含 bug

$deletedOptions = $dev->getDbTableColumnTest('zt_decision', 'deleted', 'options');
r(strpos($deletedOptions, '"value":"1"') !== false ? 'enum' : 'fail') && p() && e('enum'); // 步骤10：枚举字段的取值

r($dev->getDbTableTest('zt_nonexistent_table')) && p() && e('0'); // 步骤11：不存在的表返回空
