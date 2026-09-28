#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDbTables();
timeout=0
cid=16019

- 步骤1：任务表在清单中 @zt_task
- 步骤2：任务表的中文名 @任务
- 步骤3：任务表所属分组为执行 @execution
- 步骤4：任务表描述来自语言文件 @yes
- 步骤5：未配置描述的表描述为空 @empty
- 步骤6：英文表描述回退正常 @en
- 步骤7：字段描述可以按语言读取 @zh

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->gen(0);
zenData('config')->gen(0);

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

$dev = new devModelTest();

r($dev->getDbTablesTest('zt_task')) && p('name')  && e('zt_task');   // 步骤1：任务表在清单中
r($dev->getDbTablesTest('zt_task')) && p('title') && e('任务');       // 步骤2：任务表的中文名
r($dev->getDbTablesTest('zt_task')) && p('group') && e('execution');  // 步骤3：任务表所属分组为执行

$taskTable = $dev->getDbTablesTest('zt_task');
r(strpos($taskTable['description'], '任务表') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：任务表描述来自语言文件

$actionTable = $dev->getDbTablesTest('zt_action');
r(empty($actionTable['description']) ? 'empty' : 'fail') && p() && e('empty'); // 步骤5：未配置描述的表描述为空

$enTableDescs = $dev->getTableDescriptionsTest('en');
r(strpos($enTableDescs['task'], 'Task table') !== false ? 'en' : 'fail') && p() && e('en'); // 步骤6：英文表描述回退正常

$zhFieldDescs = $dev->getFieldDescriptionsTest('zh-cn');
r(strpos($zhFieldDescs['task']['status'], '任务状态') !== false ? 'zh' : 'fail') && p() && e('zh'); // 步骤7：字段描述可以按语言读取
