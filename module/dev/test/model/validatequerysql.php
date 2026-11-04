#!/usr/bin/env php
<?php

/**

title=测试 devModel::validateQuerySQL();
timeout=0
cid=0

- 步骤1：空 SQL 提示传入参数 @empty
- 步骤2：超过长度限制的 SQL 被拒绝 @invalid
- 步骤3：WITH 查询被拒绝 @invalid
- 步骤4：非 SELECT 语句被拒绝 @invalid
- 步骤5：多条 SQL 语句被拒绝 @invalid
- 步骤6：包含禁止关键字的 SQL 被拒绝 @invalid
- 步骤7：访问不存在的数据表被拒绝 @invalid
- 步骤8：合法的 SELECT 语句通过 @valid
- 步骤9：带 limit 的 SELECT 语句通过 @valid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->dev = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('user')->gen(1);

$dev = new devModelTest();

r($dev->validateQuerySQLTest('')) && p() && e('empty'); // 步骤1：空 SQL 提示传入参数
r($dev->validateQuerySQLTest('select ' . str_repeat('a', 10001) . ' from zt_user')) && p() && e('invalid'); // 步骤2：超过长度限制的 SQL 被拒绝
r($dev->validateQuerySQLTest('with t as (select 1 as a) select * from t')) && p() && e('invalid'); // 步骤3：WITH 查询被拒绝
r($dev->validateQuerySQLTest('update zt_task set name = "x"')) && p() && e('invalid'); // 步骤4：非 SELECT 语句被拒绝
r($dev->validateQuerySQLTest('select 1; drop table zt_task')) && p() && e('invalid'); // 步骤5：多条 SQL 语句被拒绝
r($dev->validateQuerySQLTest('select * from information_schema.tables')) && p() && e('invalid'); // 步骤6：包含禁止关键字的 SQL 被拒绝
r($dev->validateQuerySQLTest('select * from zt_no_such_table')) && p() && e('invalid'); // 步骤7：访问不存在的数据表被拒绝
r($dev->validateQuerySQLTest('select account from zt_user')) && p() && e('valid'); // 步骤8：合法的 SELECT 语句通过
r($dev->validateQuerySQLTest('select account from zt_user limit 1')) && p() && e('valid'); // 步骤9：带 limit 的 SELECT 语句通过
