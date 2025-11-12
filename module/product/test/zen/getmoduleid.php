#!/usr/bin/env php
<?php

/**

title=测试 productZen::getModuleId();
timeout=0
cid=0

- 测试步骤1:browseType为bymodule时,返回param值 @123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $app;
$productTest = new productZenTest();

r($productTest->getModuleIdTest(123, 'bymodule')) && p() && e('123'); // 测试步骤1:browseType为bymodule时,返回param值
$_COOKIE['storyModule'] = '456'; r($productTest->getModuleIdTest(0, '')) && p() && e('456'); // 测试步骤2:browseType为空且cookie有storyModule时,返回cookie值
unset($_COOKIE['storyModule']); unset($_COOKIE['storyModuleParam']); r($productTest->getModuleIdTest(123, 'bysearch')) && p() && e('0'); // 测试步骤3:browseType为bysearch时,不从cookie获取,返回0
unset($_COOKIE['storyModule']); unset($_COOKIE['storyModuleParam']); r($productTest->getModuleIdTest(123, 'bybranch')) && p() && e('0'); // 测试步骤4:browseType为bybranch时,不从cookie获取,返回0
$_COOKIE['storyModule'] = '789'; r($productTest->getModuleIdTest(0, 'unclosed')) && p() && e('789'); // 测试步骤5:browseType为unclosed且cookie有值时,返回cookie值
$app->tab = 'project'; $_COOKIE['storyModuleParam'] = '999'; unset($_COOKIE['storyModule']); r($productTest->getModuleIdTest(0, '')) && p() && e('999'); // 测试步骤6:项目tab下browseType为空且cookie有storyModuleParam时,返回storyModuleParam
$app->tab = 'product'; r($productTest->getModuleIdTest(0, 'bymodule')) && p() && e('0'); // 测试步骤7:browseType为bymodule且param为0时,返回0
unset($_COOKIE['storyModule']); unset($_COOKIE['storyModuleParam']); r($productTest->getModuleIdTest(0, '')) && p() && e('0'); // 测试步骤8:browseType为空且无cookie时,返回0