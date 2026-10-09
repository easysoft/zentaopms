#!/usr/bin/env php
<?php

/**

title=测试 devModel::getSchema();
timeout=0
cid=0

- 步骤1：当前数据库的 schema 名称 @currentSchema
- 步骤2：pgsql 驱动时使用配置的 schema @myschema
- 步骤3：pgsql 驱动未配置 schema 时使用 public @public
- 步骤4：mysql 驱动时使用数据库名称 @currentSchema
- 步骤5：kingbase 驱动时使用 public @public
- 步骤6：切换驱动后恢复原始配置 @currentSchema

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

r($dev->getSchemaTest() === $config->db->name ? 'currentSchema' : 'other') && p() && e('currentSchema'); // 步骤1：当前数据库的 schema 名称
r($dev->getSchemaWithDriverTest('postgres', 'myschema')) && p() && e('myschema');    // 步骤2：pgsql 驱动时使用配置的 schema
r($dev->getSchemaWithDriverTest('postgres')) && p() && e('public');                  // 步骤3：pgsql 驱动未配置 schema 时使用 public
r($dev->getSchemaWithDriverTest('mysql') === $config->db->name ? 'currentSchema' : 'other') && p() && e('currentSchema'); // 步骤4：mysql 驱动时使用数据库名称
r($dev->getSchemaWithDriverTest('kingbase')) && p() && e('public');                  // 步骤5：kingbase 驱动时使用 public
r($dev->getSchemaTest() === $config->db->name ? 'currentSchema' : 'other') && p() && e('currentSchema'); // 步骤6：切换驱动后恢复原始配置
