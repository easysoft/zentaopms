#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getTeamMemberPairs();
cid=1
pid=1

测试获取产品1的信息 >> po1,test1,dev1,0,1,2,3
测试获取产品2的信息 >> po2,test2,dev2,0,1,2,3
测试获取产品3的信息 >> po3,test3,dev3,0,1,2,3
测试获取产品4的信息 >> po4,test4,dev4,0,1,2,3
测试获取产品5的信息 >> po5,test5,dev5,0,1,2,3

*/

$productIDList = array('1', '2', '3', '4', '5', '1000001');

$product = new productTest('admin');

r($product->getTeamMemberPairsTest($productIDList[0])) && p('po1,test1,dev1,admin,pm92,po2,user12') && e('po1,test1,dev1,0,1,2,3'); // 测试获取产品1的信息
r($product->getTeamMemberPairsTest($productIDList[1])) && p('po2,test2,dev2,pm93,user3,po3,user13') && e('po2,test2,dev2,0,1,2,3'); // 测试获取产品2的信息
r($product->getTeamMemberPairsTest($productIDList[2])) && p('po3,test3,dev3,pm94,user4,po4,user14') && e('po3,test3,dev3,0,1,2,3'); // 测试获取产品3的信息
r($product->getTeamMemberPairsTest($productIDList[3])) && p('po4,test4,dev4,pm95,user5,po5,user15') && e('po4,test4,dev4,0,1,2,3'); // 测试获取产品4的信息
r($product->getTeamMemberPairsTest($productIDList[4])) && p('po5,test5,dev5,pm96,user6,po6,user16') && e('po5,test5,dev5,0,1,2,3'); // 测试获取产品5的信息