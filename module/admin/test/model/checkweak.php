#!/usr/bin/env php
<?php
/**

title=测试 adminModel->checkWeak();
timeout=0
cid=14977

- 测试密码使用 123456 @1
- 测试密码与用户名相同 @1
- 测试密码与手机号相同 @1
- 测试密码与电话相同 @1
- 测试密码与生日相同 @1
- 测试使用复杂密码 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(6);

su('admin');

global $config;
if(!isset($config->safe)) $config->safe = new stdClass();
$config->safe->weak = '123456';

$admin = new adminModelTest();
r($admin->checkWeakTest('user1')) && p() && e('1');
r($admin->checkWeakTest('user2')) && p() && e('1');
r($admin->checkWeakTest('user3')) && p() && e('1');
r($admin->checkWeakTest('user4')) && p() && e('1');
r($admin->checkWeakTest('user5')) && p() && e('1');
r($admin->checkWeakTest('user6')) && p() && e('0');
