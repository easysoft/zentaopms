#!/usr/bin/env php
<?php

/**

title=测试 docZen::setAclForCreateLib();
timeout=0
cid=16221

- 测试 product 类型
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 测试 project 类型
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 测试 execution 类型
 - 属性hasDefault @1
 - 属性hasOpen @0
 - 属性hasPrivate @1
- 测试 api 类型
 - 属性hasDefault @1
 - 属性hasOpen @1
 - 属性hasPrivate @1
- 测试 custom 类型
 - 属性hasDefault @0
 - 属性hasOpen @1
 - 属性hasPrivate @1
- 测试 mine 类型
 - 属性hasDefault @0
 - 属性hasOpen @0
 - 属性hasPrivate @1
 - 属性count @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->setAclForCreateLibTest('product'))   && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1'); // 测试 product 类型
r($docTest->setAclForCreateLibTest('project'))   && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1'); // 测试 project 类型
r($docTest->setAclForCreateLibTest('execution')) && p('hasDefault,hasOpen,hasPrivate') && e('1,0,1'); // 测试 execution 类型
r($docTest->setAclForCreateLibTest('api'))       && p('hasDefault,hasOpen,hasPrivate') && e('1,1,1'); // 测试 api 类型
r($docTest->setAclForCreateLibTest('custom'))    && p('hasDefault,hasOpen,hasPrivate') && e('0,1,1'); // 测试 custom 类型
r($docTest->setAclForCreateLibTest('mine'))      && p('hasDefault,hasOpen,hasPrivate,count') && e('0,0,1,1'); // 测试 mine 类型