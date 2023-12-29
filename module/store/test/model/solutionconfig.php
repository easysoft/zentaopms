#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 storeModel->solutionConfig().
cid=1

- 测试查询成功 @Success
- 测试查询无数据 @No data

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/store.class.php';

zdTable('user')->gen(5);
su('admin');

$typeList  = array('name', 'id');
$valueList = array('devops', '29');

$store = new storeTest();
r($store->solutionConfigTest($typeList[0], $valueList[0])) && p() && e('Success'); //测试查询成功
r($store->solutionConfigTest($typeList[1], $valueList[1])) && p() && e('No data'); //测试查询无数据
