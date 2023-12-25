#!/usr/bin/env php
<?php

/**

title=测试productModel->getListBySearch();
cid=0

- 测试通过空的搜索条件筛选产品数据第1条的name属性 @产品1
- 测试通过搜索条件筛选产品数据第1条的name属性 @产品1
- 测试通过不存在的搜索条件筛选产品数据第1条的name属性 @产品1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
zdTable('product')->config('product')->gen(30);
$userquery = zdTable('userquery');
$userquery->id->range(1);
$userquery->sql->range("`(( 1   AND `name`  LIKE '%产品%' ) AND ( 1  ))`");
$userquery->module->range('product');
$userquery->gen(1);
su('admin');

$queryIdList = array(0, 1, 2);

global $tester;
$tester->loadModel('product');
r($tester->product->getListBySearch($queryIdList[0])) && p('1:name') && e('产品1'); // 测试通过空的搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[1])) && p('1:name') && e('产品1'); // 测试通过搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[2])) && p('1:name') && e('产品1'); // 测试通过不存在的搜索条件筛选产品数据
