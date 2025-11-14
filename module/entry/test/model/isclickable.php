#!/usr/bin/env php
<?php

/**

title=测试 entryModel::isClickable();
timeout=0
cid=16251

- 步骤1：正常entry对象和log操作 @1
- 步骤2：正常entry对象和edit操作 @1
- 步骤3：正常entry对象和delete操作 @1
- 步骤4：空entry对象和空操作参数 @1
- 步骤5：复杂entry对象和特殊操作名 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/entry.unittest.class.php';

su('admin');

$entryTest = new entryTest();

// 准备不同类型的entry对象进行测试
$normalEntry = new stdclass();
$normalEntry->id = 1;
$normalEntry->name = '正常应用';
$normalEntry->code = 'normal_app';
$normalEntry->key = 'normal_key';

$emptyEntry = new stdclass();

$complexEntry = new stdclass();
$complexEntry->id = 999;
$complexEntry->name = '复杂应用名称-特殊字符@#$%';
$complexEntry->code = 'complex_app_123';
$complexEntry->key = 'complex_key_with_special_chars';
$complexEntry->deleted = '0';

// 执行5个测试步骤
r($entryTest->isClickableTest($normalEntry, 'log')) && p() && e('1');       // 步骤1：正常entry对象和log操作
r($entryTest->isClickableTest($normalEntry, 'edit')) && p() && e('1');      // 步骤2：正常entry对象和edit操作
r($entryTest->isClickableTest($normalEntry, 'delete')) && p() && e('1');    // 步骤3：正常entry对象和delete操作
r($entryTest->isClickableTest($emptyEntry, '')) && p() && e('1');           // 步骤4：空entry对象和空操作参数
r($entryTest->isClickableTest($complexEntry, 'custom_action_123')) && p() && e('1'); // 步骤5：复杂entry对象和特殊操作名