#!/usr/bin/env php
<?php

/**

title=测试 transferZen::printRow();
timeout=0
cid=0

- 执行transferTest模块的printRowTest方法，参数是'user', 1, $fields, $object, '', 1  @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 执行transferTest模块的printRowTest方法，参数是'user', 2, $fields, $object2, '', 1  @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 执行transferTest模块的printRowTest方法，参数是'task', 3, $fields, $object3, '', 1  @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 执行transferTest模块的printRowTest方法，参数是'user', 4, array  @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752
- 执行transferTest模块的printRowTest方法，参数是'user', 5, $fields, $emptyObject, '', 1  @Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

// 显示所有错误
error_reporting(E_ALL);
ini_set('display_errors', 1);

su('admin');

$transferTest = new transferZenTest();

// 测试步骤1：正常对象有ID情况测试异常
$fields = array('name' => array('control' => 'input', 'values' => array()));
$object = new stdClass();
$object->id = 1;
$object->name = 'Test Object';
r($transferTest->printRowTest('user', 1, $fields, $object, '', 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752');

// 测试步骤2：新对象无ID情况测试异常
$object2 = new stdClass();
$object2->name = 'New Object';
r($transferTest->printRowTest('user', 2, $fields, $object2, '', 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752');

// 测试步骤3：task模块子任务情况测试异常
$object3 = new stdClass();
$object3->name = '>Child Task';
r($transferTest->printRowTest('task', 3, $fields, $object3, '', 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752');

// 测试步骤4：空字段数组测试异常
r($transferTest->printRowTest('user', 4, array(), $object, '', 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752');

// 测试步骤5：空对象测试异常
$emptyObject = new stdClass();
r($transferTest->printRowTest('user', 5, $fields, $emptyObject, '', 1)) && p() && e('Exception:  in /home/z/repo/git/zentaopms/framework/base/router.class.php:3752');