#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

/**
title=测试获取团队成员 productModel->getTeamMemberPairs();
cid=1
pid=1
*/

zdTable('product')->gen(10);
zdTable('project')->gen(10);
zdTable('projectproduct')->gen(10);
zdTable('team')->gen(10);

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getTeamMemberPairsTest($productIDList[0])) && p('po1,test1,dev1,admin') && e('po1,test1,dev1,0'); // 测试获取产品1的团队成员信息，结果是account名作为key。
r($product->getTeamMemberPairsTest($productIDList[1])) && p('po2,test2,dev2,user3') && e('po2,test2,dev2,0'); // 测试获取产品2的团队成员信息。
r($product->getTeamMemberPairsTest($productIDList[2])) && p('po3,test3,dev3,user4') && e('po3,test3,dev3,0'); // 测试获取产品3的团队成员信息。
r($product->getTeamMemberPairsTest($productIDList[3])) && p('po4,test4,dev4,user5') && e('po4,test4,dev4,0'); // 测试获取产品4的团队成员信息。
r($product->getTeamMemberPairsTest($productIDList[4])) && p('po5,test5,dev5,user6') && e('po5,test5,dev5,0'); // 测试获取产品5的团队成员信息。
