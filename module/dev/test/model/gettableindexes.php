#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableIndexes();
timeout=0
cid=0

- 步骤1：任务表主键的列名 @id
- 步骤2：任务表主键是唯一索引 @1
- 步骤3：任务表普通索引的列名 @assignedTo
- 步骤4：自动缓存表前缀索引保留长度 @190
- 步骤5：全文索引类型 @FULLTEXT
- 步骤6：组合索引的第二列 @objectID
- 步骤7：表名为空时返回空 @0

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

r($dev->getTableIndexTest('zt_task', 'PRIMARY', 'columns.1.name'))      && p() && e('id');         // 步骤1：任务表主键的列名
r($dev->getTableIndexTest('zt_task', 'PRIMARY', 'unique'))              && p() && e('1');          // 步骤2：任务表主键是唯一索引
r($dev->getTableIndexTest('zt_task', 'assignedTo', 'columns.1.name'))   && p() && e('assignedTo'); // 步骤3：任务表普通索引的列名
r($dev->getTableIndexTest('zt_autocache', 'cache', 'columns.2.subPart')) && p() && e('190');       // 步骤4：自动缓存表前缀索引保留长度
r($dev->getTableIndexTest('zt_searchindex', 'title_content', 'type'))   && p() && e('FULLTEXT');   // 步骤5：全文索引类型
r($dev->getTableIndexTest('zt_searchindex', 'object', 'columns.2.name')) && p() && e('objectID');   // 步骤6：组合索引的第二列
r($dev->getTableIndexesTest(''))                                        && p() && e('0');          // 步骤7：表名为空时返回空
