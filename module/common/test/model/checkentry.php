#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkEntry();
timeout=0
cid=0

- 执行commonTest模块的checkEntryTest方法，参数是'', ''  @EMPTY_ENTRY
- 执行commonTest模块的checkEntryTest方法，参数是'misc', 'about'  @true
- 执行commonTest模块的checkEntryTest方法，参数是'user', 'login', '', 'testtoken'  @PARAM_CODE_MISSING
- 执行commonTest模块的checkEntryTest方法，参数是'user', 'login', 'testcode', ''  @PARAM_TOKEN_MISSING
- 执行commonTest模块的checkEntryTest方法，参数是'user', 'login', 'invalidcode', 'token123'  @EMPTY_ENTRY
- 执行commonTest模块的checkEntryTest方法，参数是'user', 'login', 'nokey', 'token123'  @EMPTY_KEY
- 执行commonTest模块的checkEntryTest方法，参数是'user', 'login', 'validip', 'token123'  @IP_DENIED

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->checkEntryTest('', '')) && p() && e('EMPTY_ENTRY');
r($commonTest->checkEntryTest('misc', 'about')) && p() && e('true');
r($commonTest->checkEntryTest('user', 'login', '', 'testtoken')) && p() && e('PARAM_CODE_MISSING');
r($commonTest->checkEntryTest('user', 'login', 'testcode', '')) && p() && e('PARAM_TOKEN_MISSING');
r($commonTest->checkEntryTest('user', 'login', 'invalidcode', 'token123')) && p() && e('EMPTY_ENTRY');
r($commonTest->checkEntryTest('user', 'login', 'nokey', 'token123')) && p() && e('EMPTY_KEY');
r($commonTest->checkEntryTest('user', 'login', 'validip', 'token123')) && p() && e('IP_DENIED');