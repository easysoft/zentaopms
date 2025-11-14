#!/usr/bin/env php
<?php

/**

title=测试 productZen::setMenu4All();
timeout=0
cid=17612

- 步骤1:普通视图类型,方法执行成功
 - 属性executionSuccess @1
 - 属性productsCount @2
- 步骤2:mhtml视图类型,设置移动端菜单
 - 属性viewType @mhtml
 - 属性executionSuccess @1
- 步骤3:空视图类型,测试默认行为
 - 属性executionSuccess @1
 - 属性productsCount @0
- 步骤4:传入产品列表,测试方法执行
 - 属性executionSuccess @1
 - 属性productsCount @3
- 步骤5:json视图类型,测试API场景
 - 属性viewType @json
 - 属性executionSuccess @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

$products = array(1 => '产品1', 2 => '产品2');

r($productTest->setMenu4AllTest('', $products)) && p('executionSuccess,productsCount') && e('1,2'); // 步骤1:普通视图类型,方法执行成功
r($productTest->setMenu4AllTest('mhtml', $products)) && p('viewType,executionSuccess') && e('mhtml,1'); // 步骤2:mhtml视图类型,设置移动端菜单
r($productTest->setMenu4AllTest('', array())) && p('executionSuccess,productsCount') && e('1,0'); // 步骤3:空视图类型,测试默认行为
r($productTest->setMenu4AllTest('', array(1 => '测试产品A', 2 => '测试产品B', 3 => '测试产品C'))) && p('executionSuccess,productsCount') && e('1,3'); // 步骤4:传入产品列表,测试方法执行
r($productTest->setMenu4AllTest('json', $products)) && p('viewType,executionSuccess') && e('json,1'); // 步骤5:json视图类型,测试API场景