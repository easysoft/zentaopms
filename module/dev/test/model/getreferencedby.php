#!/usr/bin/env php
<?php

/**

title=测试 devModel::getReferencedBy();
timeout=0
cid=0

- 步骤1：任务模块存在入向关联 @yes
- 步骤2：任务表的 parent 字段关联任务表 @parent
- 步骤3：任务表的入向关联来源表 @zt_task
- 步骤4：Bug 表的 task 字段关联任务表 @task
- 步骤5：需求表没有关联任务表 @missing
- 步骤6：不存在的模块返回空 @0

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

r(count($dev->getReferencedByTest('task')) > 0 ? 'yes' : 'fail') && p() && e('yes'); // 步骤1：任务模块存在入向关联
r($dev->getReferencedByFieldTest('task', 'task', 'column')) && p() && e('parent');   // 步骤2：任务表的 parent 字段关联任务表
r($dev->getReferencedByFieldTest('task', 'task', 'table')) && p() && e('zt_task');   // 步骤3：任务表的入向关联来源表
r($dev->getReferencedByFieldTest('task', 'bug', 'column')) && p() && e('task');      // 步骤4：Bug 表的 task 字段关联任务表
r($dev->getReferencedByFieldTest('task', 'story', 'column')) && p() && e('missing'); // 步骤5：需求表没有关联任务表
r($dev->getReferencedByTest('no_such_module')) && p() && e('0');                     // 步骤6：不存在的模块返回空
