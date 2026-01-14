#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getMetricPagination();
timeout=0
cid=18248

- 执行screenTest模块的getMetricPaginationTest方法，参数是null
 - 属性index @1
 - 属性size @5
 - 属性total @0
 - 属性pageTotal @1
- 执行screenTest模块的getMetricPaginationTest方法
 - 属性index @1
 - 属性size @5
 - 属性total @0
 - 属性pageTotal @1
- 执行screenTest模块的getMetricPaginationTest方法
 - 属性index @1
 - 属性size @12
 - 属性total @0
 - 属性pageTotal @1
- 执行screenTest模块的getMetricPaginationTest方法
 - 属性index @1
 - 属性size @10
 - 属性total @0
 - 属性pageTotal @1
- 执行screenTest模块的getMetricPaginationTest方法
 - 属性index @1
 - 属性size @12
 - 属性total @0
 - 属性pageTotal @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

r($screenTest->getMetricPaginationTest(null)) && p('index,size,total,pageTotal') && e('1,5,0,1');
r($screenTest->getMetricPaginationTest((object)array('option' => (object)array('displayType' => 'normal', 'tableOption' => (object)array(), 'card' => (object)array())))) && p('index,size,total,pageTotal') && e('1,5,0,1');
r($screenTest->getMetricPaginationTest((object)array('option' => (object)array('displayType' => 'card', 'tableOption' => (object)array(), 'card' => (object)array())))) && p('index,size,total,pageTotal') && e('1,12,0,1');
r($screenTest->getMetricPaginationTest((object)array('option' => (object)array('displayType' => 'normal', 'tableOption' => (object)array('rowNum' => 10, 'pagination' => (object)array('size' => 8)), 'card' => (object)array())))) && p('index,size,total,pageTotal') && e('1,10,0,1');
r($screenTest->getMetricPaginationTest((object)array('option' => (object)array('displayType' => 'card', 'tableOption' => (object)array(), 'card' => (object)array('countEachRow' => 3, 'countEachColumn' => 4, 'pagination' => (object)array('size' => 15)))))) && p('index,size,total,pageTotal') && e('1,12,0,1');