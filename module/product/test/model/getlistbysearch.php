#!/usr/bin/env php
<?php
/**

title=测试productModel->getListBySearch();
cid=17495

- 测试通过空的搜索条件筛选产品数据第1条的name属性 @产品1
- 测试通过空的搜索条件筛选产品数据第2条的name属性 @产品2
- 测试通过搜索条件筛选产品数据第1条的name属性 @产品1
- 测试通过搜索条件筛选产品数据第2条的name属性 @产品2
- 测试通过不存在的搜索条件筛选产品数据第1条的name属性 @产品1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('product')->loadYaml('product')->gen(30);
$userquery = zenData('userquery');
$userquery->id->range(1);
$userquery->sql->range("`(( 1   AND `name`  LIKE '%产品%' ) AND ( 1  ))`");
$userquery->module->range('product');
$userquery->gen(1);
su('admin');

$queryIdList = array(0, 1, 2);

global $tester;
$tester->loadModel('product');
r($tester->product->getListBySearch($queryIdList[0])) && p('1:name') && e('产品1'); // 测试通过空的搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[0])) && p('2:name') && e('产品2'); // 测试通过空的搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[1])) && p('1:name') && e('产品1'); // 测试通过搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[1])) && p('2:name') && e('产品2'); // 测试通过搜索条件筛选产品数据
r($tester->product->getListBySearch($queryIdList[2])) && p('1:name') && e('产品1'); // 测试通过不存在的搜索条件筛选产品数据
