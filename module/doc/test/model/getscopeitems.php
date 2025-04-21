#!/usr/bin/env php
<?php

/**

title=测试 docModel->getScopeItems();
timeout=0
cid=1

- 获取产品范围
 - 第0条的value属性 @1
 - 第0条的text属性 @产品
- 获取项目范围
 - 第1条的value属性 @2
 - 第1条的text属性 @项目
- 获取执行范围
 - 第2条的value属性 @3
 - 第2条的text属性 @执行
- 获取个人范围
 - 第3条的value属性 @4
 - 第3条的text属性 @个人
- 获取产品范围
 - 第0条的value属性 @1
 - 第0条的text属性 @产品

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->getScopeItemsTest()) && p('0:value,text') && e('1,产品'); // 获取产品范围
r($docTester->getScopeItemsTest()) && p('1:value,text') && e('2,项目'); // 获取项目范围
r($docTester->getScopeItemsTest()) && p('2:value,text') && e('3,执行'); // 获取执行范围
r($docTester->getScopeItemsTest()) && p('3:value,text') && e('4,个人'); // 获取个人范围
