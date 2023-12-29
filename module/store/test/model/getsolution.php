#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->getSolution().
cid=1

- 测试查询devops解决方案
 - 属性name @devops
 - 属性title @禅道 DevOps 解决方案
- 测试查询失败 @No data

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$typeList  = array('name', 'id');
$valueList = array('devops', '29');

$store = new storeTest();
r($store->getSolutionTest($typeList[0], $valueList[0])) && p('name,title') && e('devops,禅道 DevOps 解决方案'); //测试查询devops解决方案
r($store->getSolutionTest($typeList[1], $valueList[1])) && p()             && e('No data');                     //测试查询失败
