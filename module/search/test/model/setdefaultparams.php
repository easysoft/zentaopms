#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';

su('admin');

zdTable('user')->gen(5);
zdTable('product')->gen(10);
zdTable('project')->config('execution')->gen(10);

/**

title=测试 searchModel->setDefaultParams();
timeout=0
cid=1

- 测试用户admin的值第user条的admin属性 @A:admin
- 测试产品1的值第product条的1属性 @正常产品1
- 测试迭代101的值第execution条的101属性 @/迭代5

*/

$search = new searchTest();

$fields = array('user' => 'user', 'product' => 'product', 'execution' => 'execution');
$userParam = array();
$userParam['operator'] = '=';
$userParam['control']  = 'select';
$userParam['values']   = 'users';

$userParam = array();
$userParam['operator'] = '=';
$userParam['control']  = 'select';
$userParam['values']   = 'users';

$productParam = array();
$productParam['operator'] = '=';
$productParam['control']  = 'select';
$productParam['values']   = 'products';

$executionParam = array();
$executionParam['operator'] = '=';
$executionParam['control']  = 'select';
$executionParam['values']   = 'executions';

$params = array();
$params['user']      = $userParam;
$params['product']   = $productParam;
$params['execution'] = $executionParam;

r($search->setDefaultParamsTest($fields, $params)) && p('user:admin')    && e('A:admin');   //测试用户admin的值
r($search->setDefaultParamsTest($fields, $params)) && p('product:1')     && e('正常产品1'); //测试产品1的值
r($search->setDefaultParamsTest($fields, $params)) && p('execution:101') && e('/迭代5');     //测试迭代101的值