#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testsuite')->gen(405);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->getList();
cid=15533
pid=1

测试获取列表的个数，目前只有一个 >> 1
测试获取列表某个用例库的名称信息 >> 这是测试套件名称201

*/

$orderList = array('id_desc', 'id_asc');

$caselib = new caselibModelTest();

$list1 = $caselib->getListTest($orderList[0]);
$list2 = $caselib->getListTest($orderList[1]);

r(implode(',', array_keys($list1))) && p()           && e('402,201');             // 测试获取列表的id，目前只有一个 id_desc
r(count($list1))                    && p()           && e('2');                   // 测试获取列表的个数，目前只有一个 id_desc
r($list1)                           && p('201:name') && e('这是测试套件名称201'); // 测试获取列表 201 用例库的名称信息 id_desc
r($list1)                           && p('402:name') && e('这是测试套件名称402'); // 测试获取列表 402 用例库的名称信息 id_desc

r(implode(',', array_keys($list2))) && p()           && e('201,402');             // 测试获取列表的id，目前只有一个 id_asc
r(count($list2))                    && p()           && e('2');                   // 测试获取列表的个数，目前只有一个 id_asc
r($list2)                           && p('201:name') && e('这是测试套件名称201'); // 测试获取列表 201 用例库的名称信息 id_asc
r($list2)                           && p('402:name') && e('这是测试套件名称402'); // 测试获取列表 402 用例库的名称信息 id_asc
