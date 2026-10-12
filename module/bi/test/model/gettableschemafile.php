#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::getTableSchemaFile();
timeout=0
cid=0

- 正常对象名返回定义文件绝对路径 @1
- 路径穿越对象名被拒绝 @1
- 带扩展名的对象名被拒绝 @1
- 含斜杠的对象名被拒绝 @1
- 不存在的对象名返回空 @1
- 空对象名返回空 @1

*/

$testObj = new biModelTest();
$root    = realpath($app->getModuleRoot() . 'dataview' . DIRECTORY_SEPARATOR . 'table');

r($testObj->getTableSchemaFileTest('bug') === $root . DIRECTORY_SEPARATOR . 'bug.php')                 && p() && e(1); // 正常对象名返回定义文件绝对路径
r($testObj->getTableSchemaFileTest('../../../../../tmp/vuln014_marker') === '')                       && p() && e(1); // 路径穿越对象名被拒绝
r($testObj->getTableSchemaFileTest('bug.php') === '')                                                 && p() && e(1); // 带扩展名的对象名被拒绝
r($testObj->getTableSchemaFileTest('bug/x') === '')                                                   && p() && e(1); // 含斜杠的对象名被拒绝
r($testObj->getTableSchemaFileTest('nonexistentobject') === '')                                       && p() && e(1); // 不存在的对象名返回空
r($testObj->getTableSchemaFileTest('') === '')                                                        && p() && e(1); // 空对象名返回空
