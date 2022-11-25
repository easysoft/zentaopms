#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getProductLeftBugs();
cid=1
pid=1

测试获取buildID为11 productID为1的bug >> BUG1,BUG2,BUG3,测试单转Bug1,测试单转Bug11,SonarQube_Bug12,测试单转Bug13,SonarQube_Bug14,测试单转Bug15
测试获取buildID为12 productID为2的bug >> BUG4,BUG5,BUG6,SonarQube_Bug2
测试获取buildID为13 productID为3的bug >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,bug8,BUG9,测试单转Bug3
测试获取buildID为14 productID为4的bug >> BUG10,BUG11,BUG12,SonarQube_Bug4
测试获取buildID为15 productID为5的bug >> BUG13,BUG14,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15,测试单转Bug5
测试获取buildID为16 productID为6的bug >> bug16,BUG17,BUG18,SonarQube_Bug6
测试获取不存在的build的bug >> 0
测试获取不存在的product的bug >> 0

*/

$buildIDList   = array('11', '12', '13', '14', '15', '16', '1000001');
$productIDList = array('1', '2', '3', '4', '5', '6', '1000001');

$bug=new bugTest();
r($bug->getProductLeftBugsTest($buildIDList[0], $productIDList[0])) && p() && e('BUG1,BUG2,BUG3,测试单转Bug1,测试单转Bug11,SonarQube_Bug12,测试单转Bug13,SonarQube_Bug14,测试单转Bug15'); // 测试获取buildID为11 productID为1的bug
r($bug->getProductLeftBugsTest($buildIDList[1], $productIDList[1])) && p() && e('BUG4,BUG5,BUG6,SonarQube_Bug2');                                                                         // 测试获取buildID为12 productID为2的bug
r($bug->getProductLeftBugsTest($buildIDList[2], $productIDList[2])) && p() && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,bug8,BUG9,测试单转Bug3');         // 测试获取buildID为13 productID为3的bug
r($bug->getProductLeftBugsTest($buildIDList[3], $productIDList[3])) && p() && e('BUG10,BUG11,BUG12,SonarQube_Bug4');                                                                      // 测试获取buildID为14 productID为4的bug
r($bug->getProductLeftBugsTest($buildIDList[4], $productIDList[4])) && p() && e('BUG13,BUG14,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15,测试单转Bug5');      // 测试获取buildID为15 productID为5的bug
r($bug->getProductLeftBugsTest($buildIDList[5], $productIDList[5])) && p() && e('bug16,BUG17,BUG18,SonarQube_Bug6');                                                                      // 测试获取buildID为16 productID为6的bug
r($bug->getProductLeftBugsTest($buildIDList[6], $productIDList[1])) && p() && e('0'); // 测试获取不存在的build的bug
r($bug->getProductLeftBugsTest($buildIDList[1], $productIDList[6])) && p() && e('0'); // 测试获取不存在的product的bug