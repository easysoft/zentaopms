#!/usr/bin/env php
<?php

/**

title=测试 devModel::formatSqlDefault();
timeout=0
cid=0

- 步骤1：整型默认值不加引号 @5
- 步骤2：小数类型默认值不加引号 @5.00
- 步骤3：字符串默认值加引号 @''
- 步骤4：CURRENT_TIMESTAMP 保持函数 @CURRENT_TIMESTAMP
- 步骤5：NULL 默认值输出 NULL @NULL
- 步骤6：单引号被转义 @yes
- 步骤7：不传类型时按字符串处理 @'abc'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->dev = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

r($dev->formatSqlDefaultTest('5', 'int'))               && p() && e('5');                 // 步骤1：整型默认值不加引号
r($dev->formatSqlDefaultTest('5.00', 'decimal'))        && p() && e('5.00');              // 步骤2：小数类型默认值不加引号
r($dev->formatSqlDefaultTest('', 'varchar'))            && p() && e("''");                // 步骤3：字符串默认值加引号
r($dev->formatSqlDefaultTest('CURRENT_TIMESTAMP'))      && p() && e('CURRENT_TIMESTAMP'); // 步骤4：CURRENT_TIMESTAMP 保持函数
r($dev->formatSqlDefaultTest('NULL'))                   && p() && e('NULL');              // 步骤5：NULL 默认值输出 NULL
r(strpos($dev->formatSqlDefaultTest("a'b", 'varchar'), "\\'") !== false ? 'yes' : 'fail') && p() && e('yes'); // 步骤6：单引号被转义
r($dev->formatSqlDefaultTest('abc'))                    && p() && e("'abc'");             // 步骤7：不传类型时按字符串处理
