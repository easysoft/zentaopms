#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProductMemberPairs();
cid=1
pid=1

测试获取productID为1的bug的团队成员 >> A:admin,P:产品经理92,P:产品经理2,U:测试12
测试获取productID为2的bug的团队成员 >> P:产品经理93,U:测试3,P:研发主管3,U:测试13
测试获取productID为3的bug的团队成员 >> P:产品经理94,U:测试4,P:研发主管4,U:测试14
测试获取productID为4的bug的团队成员 >> P:产品经理95,U:测试5,P:研发主管5,U:测试15
测试获取productID为5的bug的团队成员 >> P:产品经理96,U:测试6,P:研发主管6,U:测试16
测试获取productID为6的bug的团队成员 >> P:产品经理97,U:测试7,P:研发主管7,U:测试17
测试获取不存在的product的bug的团队成员 >> 0

*/

$productIDList = array('1', '2', '3', '4','5', '6', '1000001');

$bug=new bugTest();
r($bug->getProductMemberPairsTest($productIDList[0])) && p() && e('A:admin,P:产品经理92,P:产品经理2,U:测试12'); // 测试获取productID为1的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[1])) && p() && e('P:产品经理93,U:测试3,P:研发主管3,U:测试13'); // 测试获取productID为2的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[2])) && p() && e('P:产品经理94,U:测试4,P:研发主管4,U:测试14'); // 测试获取productID为3的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[3])) && p() && e('P:产品经理95,U:测试5,P:研发主管5,U:测试15'); // 测试获取productID为4的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[4])) && p() && e('P:产品经理96,U:测试6,P:研发主管6,U:测试16'); // 测试获取productID为5的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[5])) && p() && e('P:产品经理97,U:测试7,P:研发主管7,U:测试17'); // 测试获取productID为6的bug的团队成员
r($bug->getProductMemberPairsTest($productIDList[6])) && p() && e('0');                                         // 测试获取不存在的product的bug的团队成员