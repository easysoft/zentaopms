#!/usr/bin/env php
<?php

/**

title=测试 cneModel::apiPost();
timeout=0
cid=0

- 执行cneTest模块的apiPostTest方法，参数是'/api/test', array  @1
- 执行cneTest模块的apiPostTest方法，参数是'', array  @1
- 执行cneTest模块的apiPostTest方法，参数是'/api/test', array  @1
- 执行cneTest模块的apiPostTest方法，参数是'/api/test',   @1
- 执行cneTest模块的apiPostTest方法，参数是'/api/test', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r(is_object($cneTest->apiPostTest('/api/test', array('key' => 'value')))) && p() && e('1');
r(is_object($cneTest->apiPostTest('', array()))) && p() && e('1');
r(is_object($cneTest->apiPostTest('/api/test', array()))) && p() && e('1');
r(is_object($cneTest->apiPostTest('/api/test', (object)array('key' => 'value')))) && p() && e('1');
r(is_object($cneTest->apiPostTest('/api/test', array('key' => 'value'), array('Content-Type: application/json')))) && p() && e('1');