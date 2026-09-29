#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTableDescription();
timeout=0
cid=0

- 步骤1：语言文件中的表描述 @任务表，记录执行下的任务及其状态、工时、指派人、父子任务等信息。
- 步骤2：表描述的来源是语言文件 @lang
- 步骤3：没有语言描述时使用数据表注释 @数据表注释
- 步骤4：数据表注释的来源是 comment @comment
- 步骤5：同时存在语言描述和表注释时优先使用语言描述 @语言描述
- 步骤6：语言描述为空时回退到表注释 @回退注释

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

r($dev->getTableDescriptionTest('task', 'zt_task')) && p('0') && e('任务表，记录执行下的任务及其状态、工时、指派人、父子任务等信息。'); // 步骤1：语言文件中的表描述
r($dev->getTableDescriptionTest('task', 'zt_task')) && p('1') && e('lang'); // 步骤2：表描述的来源是语言文件
r($dev->getTableDescriptionTest('no_such_module', 'zt_no_such_table', array(), array('zt_no_such_table' => '数据表注释'))) && p('0') && e('数据表注释'); // 步骤3：没有语言描述时使用数据表注释
r($dev->getTableDescriptionTest('no_such_module', 'zt_no_such_table', array(), array('zt_no_such_table' => '数据表注释'))) && p('1') && e('comment'); // 步骤4：数据表注释的来源是 comment
r($dev->getTableDescriptionTest('task', 'zt_task', array('task' => '语言描述'), array('zt_task' => '表注释'))) && p('0') && e('语言描述'); // 步骤5：同时存在语言描述和表注释时优先使用语言描述
r($dev->getTableDescriptionTest('no_such_module', 'zt_no_such_table', array('no_such_module' => ''), array('zt_no_such_table' => '回退注释'))) && p('0') && e('回退注释'); // 步骤6：语言描述为空时回退到表注释
