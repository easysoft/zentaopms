#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::getPairs();
timeout=0
cid=15534

- 测试type=all,orderBy=id_desc,获取所有用例库的键值对
 - 属性402 @这是测试套件名称402
 - 属性201 @这是测试套件名称201
- 测试type=all,orderBy=id_asc,获取所有用例库的键值对
 - 属性201 @这是测试套件名称201
 - 属性402 @这是测试套件名称402
- 测试返回的键值对数量 @2
- 测试返回的键列表(降序) @402,201

- 测试返回的键列表(升序) @201,402

- 测试type=review,获取需要评审的用例库 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testsuite')->gen(405);
zenData('user')->gen(1);

su('admin');

$caselib = new caselibModelTest();

$orderList = array('id_desc', 'id_asc');

r($caselib->getPairsTest('all', $orderList[0], null)) && p('402,201') && e('这是测试套件名称402,这是测试套件名称201'); // 测试type=all,orderBy=id_desc,获取所有用例库的键值对
r($caselib->getPairsTest('all', $orderList[1], null)) && p('201,402') && e('这是测试套件名称201,这是测试套件名称402'); // 测试type=all,orderBy=id_asc,获取所有用例库的键值对
r(count($caselib->getPairsTest('all', $orderList[0], null))) && p() && e('2'); // 测试返回的键值对数量
r(implode(',', array_keys($caselib->getPairsTest('all', $orderList[0], null)))) && p() && e('402,201'); // 测试返回的键列表(降序)
r(implode(',', array_keys($caselib->getPairsTest('all', $orderList[1], null)))) && p() && e('201,402'); // 测试返回的键列表(升序)
r($caselib->getPairsTest('review', $orderList[0], null)) && p() && e('0'); // 测试type=review,获取需要评审的用例库