#!/usr/bin/env php
<?php

/**

title=测试 devModel::getDbTableSQL();
timeout=0
cid=16021

- 步骤1：SQL 以 CREATE TABLE 开头 @yes
- 步骤2：表描述不写进 SQL @yes
- 步骤3：自增主键不输出 DEFAULT @yes
- 步骤4：字段标题和描述写成注释 @yes
- 步骤5：关联关系写入字段注释 @yes
- 步骤6：主键定义 @yes
- 步骤7：普通索引定义 @yes
- 步骤8：不存在的表返回空 @0
- 步骤9：前缀索引保留长度 @yes
- 步骤10：全文索引定义 @yes

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
$sql = $dev->getDbTableSQLTest('zt_task');

r(strpos($sql, "CREATE TABLE `zt_task` (") === 0 ? 'yes' : 'fail') && p() && e('yes');             // 步骤1：SQL 以 CREATE TABLE 开头
r(strpos($sql, '任务表，记录执行下的任务') === false ? 'yes' : 'fail') && p() && e('yes'); // 步骤2：表描述不写进 SQL
r(strpos($sql, '`id` int unsigned NOT NULL AUTO_INCREMENT,') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤3：自增主键不输出 DEFAULT
r(strpos($sql, '-- 所属项目 | 所属项目，对应 zt_project.id') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤4：字段标题和描述写成注释
r(strpos($sql, '关联：zt_project.id') !== false ? 'yes' : 'fail') && p() && e('yes');            // 步骤5：关联关系写入字段注释
r(strpos($sql, 'PRIMARY KEY (`id`)') !== false ? 'yes' : 'fail') && p() && e('yes');             // 步骤6：主键定义
r(strpos($sql, 'KEY `assignedTo` (`assignedTo`)') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤7：普通索引定义

r($dev->getDbTableSQLTest('zt_nonexistent_table')) && p() && e('0'); // 步骤8：不存在的表返回空

$prefixSQL = $dev->getDbTableSQLTest('zt_autocache');
r(strpos($prefixSQL, '`fields`(190)') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤9：前缀索引保留长度

$fulltextSQL = $dev->getDbTableSQLTest('zt_searchindex');
r(strpos($fulltextSQL, 'FULLTEXT KEY `title_content` (`title`, `content`)') !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤10：全文索引定义
