#!/usr/bin/env php
<?php

/**

title=测试 aiModel::generateToken();
timeout=0
cid=0

- 步骤1：普通 token 生成前缀为 ek-属性prefix @ek-
- 步骤2：adminToken 生成前缀为 ak-属性prefix @ak-
- 步骤3：payload 中 app_id 与 setting 一致属性app_id @test-app-id
- 步骤4：payload 中 user_id 与当前登录用户一致属性user_id @1
- 步骤5：hash 校验正确属性hash_valid @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$baseSetting = (object)array(
    'token'  => 'user-token-secret',
    'appID'  => 'test-app-id',
);

/* 步骤1：普通 token 生成前缀为 ek- */
r($aiTest->generateTokenTest($baseSetting)) && p('prefix') && e('ek-'); // 步骤1：普通 token 生成前缀为 ek-

/* 步骤2：adminToken 生成前缀为 ak- */
$adminSetting = (object)array(
    'token'      => 'user-token-secret',
    'adminToken' => 'admin-token-secret',
    'appID'      => 'test-app-id',
);
r($aiTest->generateTokenTest($adminSetting)) && p('prefix') && e('ak-'); // 步骤2：adminToken 生成前缀为 ak-

/* 步骤3：payload 中 app_id 与 setting 一致 */
r($aiTest->generateTokenTest($baseSetting)) && p('app_id') && e('test-app-id'); // 步骤3：payload 中 app_id 与 setting 一致

/* 步骤4：payload 中 user_id 与当前登录用户一致 */
r($aiTest->generateTokenTest($baseSetting)) && p('user_id') && e('1'); // 步骤4：payload 中 user_id 与当前登录用户一致

/* 步骤5：hash 校验正确 */
r($aiTest->generateTokenTest($baseSetting)) && p('hash_valid') && e('1'); // 步骤5：hash 校验正确