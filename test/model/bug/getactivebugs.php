#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getActiveBugs();
cid=1
pid=1

查询产品1 2 3 不存在的产品1000001下 且不排除bug的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG2,BUG1
查询产品1 2 3 不存在的产品1000001下 且排除bug2的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG1
查询产品1 2 3 不存在的产品1000001下 且排除bug3 8的bug >> BUG9,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG2,BUG1
查询产品1 3下 且不排除bug的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG2,BUG1
查询产品1 3下 且排除bug2的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG1
查询产品1 3下 且排除bug3 8的bug >> BUG9,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG2,BUG1
查询产品1下 且不排除bug的bug >> BUG3,BUG2,BUG1
查询产品1下 且排除bug2的bug >> BUG3,BUG1
查询产品1下 且排除bug3 8的bug >> BUG2,BUG1
查询不存在的产品1000001下 且不排除bug的bug >> 0
查询不存在的产品1000001下 且排除bug2的bug >> 0
查询不存在的产品1000001下 且排除bug3 8的bug >> 0

*/

$productIDList = array('1,2,3,1000001', '1,3', '1', '1000001');
$statusList = array('', '2', '3,8');

$bug=new bugTest();

r($bug->getActiveBugsTest($productIDList[0], $statusList[0])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG2,BUG1'); // 查询产品1 2 3 不存在的产品1000001下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[0], $statusList[1])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG1');      // 查询产品1 2 3 不存在的产品1000001下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[0], $statusList[2])) && p() && e('BUG9,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG2,BUG1');           // 查询产品1 2 3 不存在的产品1000001下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[1], $statusList[0])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG2,BUG1');                // 查询产品1 3下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[1], $statusList[1])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG1');                     // 查询产品1 3下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[1], $statusList[2])) && p() && e('BUG9,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG2,BUG1');                          // 查询产品1 3下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[2], $statusList[0])) && p() && e('BUG3,BUG2,BUG1'); // 查询产品1下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[2], $statusList[1])) && p() && e('BUG3,BUG1');      // 查询产品1下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[2], $statusList[2])) && p() && e('BUG2,BUG1');      // 查询产品1下 且排除bug3 8的bug
r($bug->getActiveBugsTest($productIDList[3], $statusList[0])) && p() && e('0');              // 查询不存在的产品1000001下 且不排除bug的bug
r($bug->getActiveBugsTest($productIDList[3], $statusList[1])) && p() && e('0');              // 查询不存在的产品1000001下 且排除bug2的bug
r($bug->getActiveBugsTest($productIDList[3], $statusList[2])) && p() && e('0');              // 查询不存在的产品1000001下 且排除bug3 8的bug