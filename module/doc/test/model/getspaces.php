#!/usr/bin/env php
<?php

/**

title=测试 docModel::getSpaces();
timeout=0
cid=16126

- 测试获取all类型的空间返回空间数组长度 @0
- 测试获取mine类型的空间返回空间数组长度 @0
- 测试获取custom类型的空间返回空间数组长度 @0
- 测试获取product类型的空间返回空间数组长度 @0
- 测试获取project类型的空间返回空间数组长度 @0
- 测试获取execution类型但spaceID为0的情况返回空间数组长度 @0
- 测试无效类型的边界情况返回spaceID属性1 @999

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTester = new docModelTest();
r($docTester->getSpacesTest('all', 0)) && p('0') && e('0');          // 测试获取all类型的空间返回空间数组长度
r($docTester->getSpacesTest('mine', 1)) && p('0') && e('0');         // 测试获取mine类型的空间返回空间数组长度
r($docTester->getSpacesTest('custom', 2)) && p('0') && e('0');       // 测试获取custom类型的空间返回空间数组长度
r($docTester->getSpacesTest('product', 1)) && p('0') && e('0');      // 测试获取product类型的空间返回空间数组长度
r($docTester->getSpacesTest('project', 1)) && p('0') && e('0');      // 测试获取project类型的空间返回空间数组长度
r($docTester->getSpacesTest('execution', 0)) && p('0') && e('0');    // 测试获取execution类型但spaceID为0的情况返回空间数组长度
r($docTester->getSpacesTest('invalid', 999)) && p('1') && e('999');  // 测试无效类型的边界情况返回spaceID