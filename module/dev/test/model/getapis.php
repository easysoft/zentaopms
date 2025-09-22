#!/usr/bin/env php
<?php

/**

title=测试 devModel::getAPIs();
timeout=0
cid=0

- 测试todo模块第一个方法名第0条的name属性 @create
- 测试product模块第一个方法名第0条的name属性 @index
- 测试user模块第一个方法名第0条的name属性 @view
- 测试API的POST标识第0条的post属性 @0
- 测试API信息结构验证属性isArray @1
- 测试common模块特殊处理 @array
- 测试dev模块特殊处理 @array
- 测试不存在模块异常处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$devTest = new devTest();

r($devTest->getAPIsTest('todo')) && p('0:name') && e('create');         // 测试todo模块第一个方法名
r($devTest->getAPIsTest('product')) && p('0:name') && e('index');       // 测试product模块第一个方法名
r($devTest->getAPIsTest('user')) && p('0:name') && e('view');           // 测试user模块第一个方法名
r($devTest->getAPIsTest('story')) && p('0:name') && e('create');        // 测试story模块第一个方法名
r($devTest->getAPIsTest('task')) && p('0:name') && e('create');         // 测试task模块第一个方法名
r($devTest->getAPIsStructureTest('todo')) && p('isArray') && e('1');    // 测试API信息结构验证
r($devTest->getAPIsTest('common')) && p() && e('array');                // 测试common模块特殊处理
r($devTest->getAPIsTest('dev')) && p() && e('array');                   // 测试dev模块特殊处理