#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getActiveAndPostponedBugs();
cid=1
pid=1

查询产品1 2 3 不存在的产品1000001 execution为101下的bug >> BUG3,BUG2,BUG1
查询产品1 2 3 不存在的产品1000001 execution为102下的bug >> BUG6,BUG5,BUG4
查询产品1 2 3 不存在的产品1000001 execution为103下的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询产品1 3 execution为101下的bug >> BUG3,BUG2,BUG1
查询产品1 3 execution为102下的bug >> 0
查询产品1 3 execution为103下的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询产品1 execution为101下的bug >> BUG3,BUG2,BUG1
查询产品1 execution为102下的bug >> 0
查询产品1 execution为103下的bug >> 0
查询不存在的产品1000001 execution为101下的bug >> 0
查询不存在的产品1000001 execution为102下的bug >> 0
查询不存在的产品1000001 execution为103下的bug >> 0

*/

$productIDList = array('1,2,3,1000001', '1,3', '1', '1000001');
$executionList = array('101', '102', '103');

$bug=new bugTest();

r($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[0])) && p() && e('BUG3,BUG2,BUG1'); // 查询产品1 2 3 不存在的产品1000001 execution为101下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[1])) && p() && e('BUG6,BUG5,BUG4'); // 查询产品1 2 3 不存在的产品1000001 execution为102下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[0], $executionList[2])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询产品1 2 3 不存在的产品1000001 execution为103下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[0])) && p() && e('BUG3,BUG2,BUG1'); // 查询产品1 3 execution为101下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[1])) && p() && e('0');              // 查询产品1 3 execution为102下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[1], $executionList[2])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询产品1 3 execution为103下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[2], $executionList[0])) && p() && e('BUG3,BUG2,BUG1'); // 查询产品1 execution为101下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[2], $executionList[1])) && p() && e('0');              // 查询产品1 execution为102下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[2], $executionList[2])) && p() && e('0');              // 查询产品1 execution为103下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[3], $executionList[0])) && p() && e('0');              // 查询不存在的产品1000001 execution为101下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[3], $executionList[1])) && p() && e('0');              // 查询不存在的产品1000001 execution为102下的bug
r($bug->getActiveAndPostponedBugsTest($productIDList[3], $executionList[2])) && p() && e('0');              // 查询不存在的产品1000001 execution为103下的bug