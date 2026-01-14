#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkEntry();
timeout=0
cid=15652

- 测试步骤1:缺少模块参数 @EMPTY_ENTRY
- 测试步骤2:缺少方法参数 @EMPTY_ENTRY
- 测试步骤3:访问开放方法misc.about @1
- 测试步骤4:缺少code参数 @PARAM_CODE_MISSING
- 测试步骤5:缺少token参数 @PARAM_TOKEN_MISSING
- 测试步骤6:code不存在 @EMPTY_ENTRY
- 测试步骤7:entry的key为空 @EMPTY_KEY
- 测试步骤8:IP地址被拒绝 @IP_DENIED
- 测试步骤9:token无效 @INVALID_TOKEN
- 测试步骤10:账户未绑定 @ACCOUNT_UNBOUND

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('checkentry/entry', false, 2)->gen(10);
zenData('user')->loadYaml('checkentry/user', false, 2)->gen(10);

su('admin');

$commonTest = new commonModelTest();

r($commonTest->checkEntryTest('', '', '', '')) && p() && e('EMPTY_ENTRY'); // 测试步骤1:缺少模块参数
r($commonTest->checkEntryTest('user', '', '', '')) && p() && e('EMPTY_ENTRY'); // 测试步骤2:缺少方法参数
r($commonTest->checkEntryTest('misc', 'about', '', '')) && p() && e('1'); // 测试步骤3:访问开放方法misc.about
r($commonTest->checkEntryTest('user', 'login', '', '')) && p() && e('PARAM_CODE_MISSING'); // 测试步骤4:缺少code参数
r($commonTest->checkEntryTest('user', 'login', 'validcode', '')) && p() && e('PARAM_TOKEN_MISSING'); // 测试步骤5:缺少token参数
r($commonTest->checkEntryTest('user', 'login', 'nonexistcode', 'token123')) && p() && e('EMPTY_ENTRY'); // 测试步骤6:code不存在
r($commonTest->checkEntryTest('user', 'login', 'nokey', 'token123')) && p() && e('EMPTY_KEY'); // 测试步骤7:entry的key为空
r($commonTest->checkEntryTest('user', 'login', 'validip', 'token123')) && p() && e('IP_DENIED'); // 测试步骤8:IP地址被拒绝
r($commonTest->checkEntryTest('user', 'login', 'invalidtoken', 'token123')) && p() && e('INVALID_TOKEN'); // 测试步骤9:token无效
r($commonTest->checkEntryTest('user', 'login', 'unboundaccount', 'token123')) && p() && e('ACCOUNT_UNBOUND'); // 测试步骤10:账户未绑定