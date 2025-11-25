#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getViewableObject();
timeout=0
cid=15189

- 测试无效对象类型 @0
- 测试空字符串对象类型 @0
- 测试NULL对象类型 @0
- 测试不支持的对象类型 @0
- 测试多个无效对象类型 @0

*/

$bi = new biTest();

r($bi->getViewableObjectTest('invalid'))  && p() && e('0');         // 测试无效对象类型
r($bi->getViewableObjectTest(''))         && p() && e('0');         // 测试空字符串对象类型  
r($bi->getViewableObjectTest(null))       && p() && e('0');         // 测试NULL对象类型
r($bi->getViewableObjectTest('unknown'))  && p() && e('0');         // 测试不支持的对象类型
r($bi->getViewableObjectTest('test'))     && p() && e('0');         // 测试多个无效对象类型