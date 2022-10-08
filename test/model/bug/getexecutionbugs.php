#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getExecutionBugs();
cid=1
pid=1

测试获取执行ID为101的bug >> BUG3,BUG2,BUG1
测试获取执行ID为102的bug >> BUG6,BUG5,BUG4
测试获取执行ID为103的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
测试获取执行ID为104的bug >> BUG12,BUG11,BUG10
测试获取执行ID为105的bug >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15,BUG14,BUG13
测试获取执行ID为106的bug >> BUG18,BUG17,bug16
测试获取不存在的执行的bug >> 0

*/

$executionIDList = array('101', '102', '103', '104', '105', '106', '1000001');

$bug=new bugTest();
r($bug->getExecutionBugsTest($executionIDList[0])) && p() && e('BUG3,BUG2,BUG1');     // 测试获取执行ID为101的bug
r($bug->getExecutionBugsTest($executionIDList[1])) && p() && e('BUG6,BUG5,BUG4');     // 测试获取执行ID为102的bug
r($bug->getExecutionBugsTest($executionIDList[2])) && p() && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7');    // 测试获取执行ID为103的bug
r($bug->getExecutionBugsTest($executionIDList[3])) && p() && e('BUG12,BUG11,BUG10');  // 测试获取执行ID为104的bug
r($bug->getExecutionBugsTest($executionIDList[4])) && p() && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;15,BUG14,BUG13'); // 测试获取执行ID为105的bug
r($bug->getExecutionBugsTest($executionIDList[5])) && p() && e('BUG18,BUG17,bug16');  // 测试获取执行ID为106的bug
r($bug->getExecutionBugsTest($executionIDList[6])) && p() && e('0');                  // 测试获取不存在的执行的bug