#!/usr/bin/env php
<?php

/**

title=- 执行transferTest模块的buildNextListTest方法，参数是$emptyList, 0, $fieldsArray, 1, 'task'  @TypeError: transferZen::buildNextList(): Argument
timeout=0
cid=3

- 执行transferTest模块的buildNextListTest方法，参数是$emptyList, 0, $fieldsArray, 1, 'task'  @TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given

- 执行transferTest模块的buildNextListTest方法，参数是$normalList, 0, $fieldsArray, 1, 'task'  @TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given

- 执行transferTest模块的buildNextListTest方法，参数是$normalList, 0, $fieldsString, 1, 'task'  @TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given

- 执行transferTest模块的buildNextListTest方法，参数是$normalList, 1, $fieldsArray, 1, 'task'  @TypeError: transferZen::printRow(): Argument #3 ($fields) must be of type array, string given, called in /home/z/repo/git/zentaopms/module/transfer/zen.php on line 159

- 执行transferTest模块的buildNextListTest方法，参数是$normalList, 10, $fieldsArray, 1, 'task'  @TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transferzen.unittest.class.php';

su('admin');

$transferTest = new transferZenTest();

// 准备测试数据
$emptyList = array();

$normalList = array(
    1 => (object)array('id' => 1, 'name' => 'Test Item 1', 'status' => 'open'),
    2 => (object)array('id' => 2, 'name' => 'Test Item 2', 'status' => 'doing'),
    3 => (object)array('id' => 3, 'name' => 'Test Item 3', 'status' => 'done')
);

$fieldsArray = array(
    'name' => array('control' => 'input', 'values' => array()),
    'status' => array('control' => 'select', 'values' => array('open' => '未开始', 'doing' => '进行中'))
);

$fieldsString = 'name,status';

r($transferTest->buildNextListTest($emptyList, 0, $fieldsArray, 1, 'task')) && p() && e('TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given');
r($transferTest->buildNextListTest($normalList, 0, $fieldsArray, 1, 'task')) && p() && e('TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given');
r($transferTest->buildNextListTest($normalList, 0, $fieldsString, 1, 'task')) && p() && e('TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given');
r($transferTest->buildNextListTest($normalList, 1, $fieldsArray, 1, 'task')) && p() && e('TypeError: transferZen::printRow(): Argument #3 ($fields) must be of type array, string given, called in /home/z/repo/git/zentaopms/module/transfer/zen.php on line 159');
r($transferTest->buildNextListTest($normalList, 10, $fieldsArray, 1, 'task')) && p() && e('TypeError: transferZen::buildNextList(): Argument #3 ($fields) must be of type string, array given');