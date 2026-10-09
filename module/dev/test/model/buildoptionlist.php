#!/usr/bin/env php
<?php

/**

title=测试 devModel::buildOptionList();
timeout=0
cid=0

- 步骤1：枚举字段的取值列表 @wait,doing,done
- 步骤2：枚举取值对应的语言标签 @未开始,进行中,已完成
- 步骤3：没有枚举选项时返回空 @0
- 步骤4：语言文件中没有的取值直接使用原值 @zzz
- 步骤5：字段没有语言标签时使用原值 @wait
- 步骤6：别名模块的语言标签生效 @未开始

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app, $lang, $config;
$lang->productCommon = $config->productCommonList[$app->clientLang][PRODUCT_KEY];
$lang->dev           = new stdClass();
baseRouter::$loadedLangs = array();
$app->loadLang('dev');
$app->loadLang('task');

su('admin');

zenData('task')->gen(1);

$dev = new devModelTest();

$enumField = array('type' => 'enum', 'options' => array('enum' => 'wait,doing,done'));

r($dev->buildOptionListTest('status', $enumField, 'task')) && p('0:value;1:value;2:value') && e('wait,doing,done'); // 步骤1：枚举字段的取值列表
r($dev->buildOptionListTest('status', $enumField, 'task')) && p('0:label;1:label;2:label') && e('未开始,进行中,已完成'); // 步骤2：枚举取值对应的语言标签
r($dev->buildOptionListTest('status', array('options' => array()), 'task')) && p() && e('0'); // 步骤3：没有枚举选项时返回空
r($dev->buildOptionListTest('status', array('options' => array('enum' => 'zzz')), 'task')) && p('0:label') && e('zzz'); // 步骤4：语言文件中没有的取值直接使用原值
r($dev->buildOptionListTest('zzzz', $enumField, 'task')) && p('0:label') && e('wait'); // 步骤5：字段没有语言标签时使用原值
r($dev->buildOptionListTest('status', $enumField, 'zzmod', 'task')) && p('0:label') && e('未开始'); // 步骤6：别名模块的语言标签生效
