#!/usr/bin/env php
<?php

/**

title=测试 devModel::isSensitiveField();
timeout=0
cid=0

- 步骤1：密码字段需要打码 @1
- 步骤2：字段名包含密码关键字需要打码 @1
- 步骤3：大小写不敏感 @1
- 步骤4：访问令牌字段需要打码 @1
- 步骤5：普通字段不打码 @0
- 步骤6：空字段名不打码 @0

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

r($dev->isSensitiveFieldTest('password'))     && p() && e('1'); // 步骤1：密码字段需要打码
r($dev->isSensitiveFieldTest('userPassword')) && p() && e('1'); // 步骤2：字段名包含密码关键字需要打码
r($dev->isSensitiveFieldTest('PASSWORD'))     && p() && e('1'); // 步骤3：大小写不敏感
r($dev->isSensitiveFieldTest('accessToken'))  && p() && e('1'); // 步骤4：访问令牌字段需要打码
r($dev->isSensitiveFieldTest('account'))      && p() && e('0'); // 步骤5：普通字段不打码
r($dev->isSensitiveFieldTest(''))             && p() && e('0'); // 步骤6：空字段名不打码
