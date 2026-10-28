#!/usr/bin/env php
<?php

/**

title=测试 actionModel::redactHistory();
timeout=0

- 密码字段读取时被脱敏 @******
- 下划线密码字段读取时被脱敏 @******
- API 密钥字段读取时被脱敏 @******
- 普通字段保持不变 @normal-old

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_HISTORY)->where('action')->eq(99019)->exec();
$tester->dao->insert(TABLE_HISTORY)->set('action')->eq(99019)->set('field')->eq('password')->set('old')->eq('old-md5')->set('new')->eq('new-md5')->set('diff')->eq('password-diff')->exec();
$tester->dao->insert(TABLE_HISTORY)->set('action')->eq(99019)->set('field')->eq('password_plain')->set('old')->eq('old-plain')->set('new')->eq('new-plain')->set('diff')->eq('plain-diff')->exec();
$tester->dao->insert(TABLE_HISTORY)->set('action')->eq(99019)->set('field')->eq('apiKey')->set('old')->eq('old-key')->set('new')->eq('new-key')->set('diff')->eq('key-diff')->exec();
$tester->dao->insert(TABLE_HISTORY)->set('action')->eq(99019)->set('field')->eq('name')->set('old')->eq('normal-old')->set('new')->eq('normal-new')->set('diff')->eq('')->exec();

$actionTest = new actionModelTest();
$histories  = $actionTest->getHistoryTest(99019);

r($histories[99019][0]) && p('old,new,diff') && e('******,******,******');
r($histories[99019][1]) && p('old,new,diff') && e('******,******,******');
r($histories[99019][2]) && p('old,new,diff') && e('******,******,******');
r($histories[99019][3]) && p('old,new,diff') && e('normal-old,normal-new,');

$tester->dao->delete()->from(TABLE_HISTORY)->where('action')->eq(99019)->exec();
