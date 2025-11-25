#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printBack();
timeout=0
cid=15689

- 测试步骤1:检查printBack方法是否存在 @1
- 测试步骤2:检查printBack方法是否为静态方法 @1
- 测试步骤3:检查printBack方法是否为公共方法 @1
- 测试步骤4:检查printBack方法参数数量 @3
- 测试步骤5:在onlybody模式下调用printBack返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->printBackMetaTest('exists'))      && p() && e('1');  // 测试步骤1:检查printBack方法是否存在
r($commonTest->printBackMetaTest('static'))      && p() && e('1');  // 测试步骤2:检查printBack方法是否为静态方法
r($commonTest->printBackMetaTest('public'))      && p() && e('1');  // 测试步骤3:检查printBack方法是否为公共方法
r($commonTest->printBackMetaTest('paramCount'))  && p() && e('3');  // 测试步骤4:检查printBack方法参数数量
r($commonTest->printBackTest('index.php', '', '', true))  && p() && e('0');  // 测试步骤5:在onlybody模式下调用printBack返回false