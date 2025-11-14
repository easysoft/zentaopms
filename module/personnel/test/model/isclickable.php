#!/usr/bin/env php
<?php

/**

title=测试 personnelModel::isClickable();
timeout=0
cid=17337

- 执行personnelTest模块的isClickableTest方法，参数是$whitelistObject, 'unbindWhitelist'  @1
- 执行personnelTest模块的isClickableTest方法，参数是$whitelistObject, ''  @1
- 执行personnelTest模块的isClickableTest方法，参数是$emptyObject, 'addWhitelist'  @1
- 执行personnelTest模块的isClickableTest方法，参数是$whitelistObject, 'special_chars'  @1
- 执行personnelTest模块的isClickableTest方法，参数是$whitelistObject, '123456'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

su('admin');

$personnelTest = new personnelTest();

$whitelistObject = new stdclass();
$whitelistObject->id = 1;
$whitelistObject->account = 'testuser';
$whitelistObject->objectType = 'project';

$emptyObject = new stdclass();

r($personnelTest->isClickableTest($whitelistObject, 'unbindWhitelist')) && p() && e('1');
r($personnelTest->isClickableTest($whitelistObject, '')) && p() && e('1');
r($personnelTest->isClickableTest($emptyObject, 'addWhitelist')) && p() && e('1');
r($personnelTest->isClickableTest($whitelistObject, 'special_chars')) && p() && e('1');
r($personnelTest->isClickableTest($whitelistObject, '123456')) && p() && e('1');