#!/usr/bin/env php
<?php

/**

title=测试 searchModel->setDefaultParams();
timeout=0
cid=18310

- 测试设置 products 的默认参数 @1,2,3,4,5,6,7,8,9,10,null

- 测试设置 users 的默认参数 @admin,user1,user2,user3,user4,$me,null

- 测试设置 executions 的默认参数 @,null

- 测试设置 空数组 的默认参数 @,null

- 测试设置 空字符串 的默认参数 @0,null

- 测试设置 array(1) 的默认参数 @ZERO
- 测试设置 nonull 的默认参数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('project')->gen(30);
zenData('projectproduct')->gen(10);
zenData('product')->gen(10);

su('admin');

$fields1 = array('product'    => 'product');
$fields2 = array('assignedTo' => 'assignedTo');
$fields3 = array('execution'  => 'execution');
$fields4 = array('project'    => 'project');
$fields5 = array('product'    => 'product');
$fields6 = array('product'    => 'product');
$fields7 = array('product'    => 'product');
$params1 = array('product'    => array('values' => 'products'));
$params2 = array('assignedTo' => array('values' => 'users'));
$params3 = array('execution'  => array('values' => 'executions'));
$params4 = array('project'    => array('values' => array()));
$params5 = array('product'    => array('values' => array('')));
$params6 = array('product'    => array('values' => array(1)));
$params7 = array('product'    => array('values' => array(''), 'nonull' => true));

$search = new searchModelTest();
r($search->setDefaultParamsTest($fields1, $params1)) && p('0') && e('1,2,3,4,5,6,7,8,9,10,null');              // 测试设置 products 的默认参数
r($search->setDefaultParamsTest($fields2, $params2)) && p('0') && e('admin,user1,user2,user3,user4,$me,null'); // 测试设置 users 的默认参数
r($search->setDefaultParamsTest($fields3, $params3)) && p('0') && e(',null');                                  // 测试设置 executions 的默认参数
r($search->setDefaultParamsTest($fields4, $params4)) && p('0') && e(',null');                                  // 测试设置 空数组 的默认参数
r($search->setDefaultParamsTest($fields5, $params5)) && p('0') && e('0,null');                                 // 测试设置 空字符串 的默认参数
r($search->setDefaultParamsTest($fields6, $params6)) && p('0') && e('ZERO');                                   // 测试设置 array(1) 的默认参数
r($search->setDefaultParamsTest($fields7, $params7)) && p('0') && e('0');                                      // 测试设置 nonull 的默认参数