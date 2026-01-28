#!/usr/bin/env php
<?php

/**

title=测试 docModel->getObjectsByDoc();
cid=16116

- 测试传入空的文档ID数组时，获取的项目数据 @0
- 测试传入空的文档ID数组时，获取的执行数据 @0
- 测试传入空的文档ID数组时，获取的产品数据 @0
- 测试传入文档ID数组时，获取的项目数据属性11 @敏捷项目1
- 测试传入文档ID数组时，获取的执行数据属性101 @迭代5
- 测试传入文档ID数组时，获取的产品数据 @0
- 测试传入文档ID数组中带有不存在数据时，获取的项目数据属性11 @敏捷项目1
- 测试传入文档ID数组中带有不存在数据时，获取的执行数据属性101 @迭代5
- 测试传入文档ID数组中带有不存在数据时，获取的产品数据属性1 @产品1
- 测试传入文档ID数组并且不存在数据时，获取的项目数据 @0
- 测试传入文档ID数组并且不存在数据时，获取的执行数据 @0
- 测试传入文档ID数组并且不存在数据时，获取的产品数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('product')->loadYaml('product')->gen(5);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$docIdList[0] = array();
$docIdList[1] = range(1, 30);
$docIdList[2] = range(1, 60);
$docIdList[3] = range(61, 80);

$docTester = new docModelTest();

list($projects, $executions, $products) = $docTester->getObjectsByDocTest($docIdList[0]);
r($projects)   && p() && e('0'); // 测试传入空的文档ID数组时，获取的项目数据
r($executions) && p() && e('0'); // 测试传入空的文档ID数组时，获取的执行数据
r($products)   && p() && e('0'); // 测试传入空的文档ID数组时，获取的产品数据

list($projects, $executions, $products) = $docTester->getObjectsByDocTest($docIdList[1]);
r($projects)   && p('11')  && e('敏捷项目1'); // 测试传入文档ID数组时，获取的项目数据
r($executions) && p('101') && e('迭代5');     // 测试传入文档ID数组时，获取的执行数据
r($products)   && p()      && e('0');         // 测试传入文档ID数组时，获取的产品数据

list($projects, $executions, $products) = $docTester->getObjectsByDocTest($docIdList[2]);
r($projects)   && p('11')  && e('敏捷项目1'); // 测试传入文档ID数组中带有不存在数据时，获取的项目数据
r($executions) && p('101') && e('迭代5');     // 测试传入文档ID数组中带有不存在数据时，获取的执行数据
r($products)   && p('1')   && e('产品1');     // 测试传入文档ID数组中带有不存在数据时，获取的产品数据

list($projects, $executions, $products) = $docTester->getObjectsByDocTest($docIdList[3]);
r($projects)   && p()  && e('0'); // 测试传入文档ID数组并且不存在数据时，获取的项目数据
r($executions) && p()  && e('0'); // 测试传入文档ID数组并且不存在数据时，获取的执行数据
r($products)   && p()  && e('0'); // 测试传入文档ID数组并且不存在数据时，获取的产品数据
