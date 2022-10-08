#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getModuleBugs();
cid=1
pid=1

查询产品1 2 3 不存在的产品10001 与模块1821, 1825, 1831 不存在的模块1000001下的bug >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG4,BUG1
查询产品1 2 3 不存在的产品10001 与模块1821, 1825下的bug >> BUG4,BUG1
查询产品1 2 3 不存在的产品10001 与不存在的模块1000001下的bug >> 0
查询产品1 3与模块1821, 1825, 1831 不存在的模块1000001下的bug >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG1
查询产品1 3与模块1821, 1825下的bug >> BUG1
查询产品1 3与模块不存在的模块1000001下的bug >> 0
查询不存在的产品10001 与模块1821, 1825, 1831 不存在的模块1000001下的bug >> 0
查询不存在的产品10001 与模块1821, 1825下的bug >> 0
查询不存在的产品10001 与不存在的模块1000001下的bug >> 0

*/

$productIDList = array('1,2,3,1000001', '1,3', '1000001');
$moduleIDList  = array('1821,1825,1831,1000001', '1821, 1825', '1000001');

$bug=new bugTest();

r($bug->getModuleBugsTest($productIDList[0], $moduleIDList[0])) && p('title') && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG4,BUG1'); // 查询产品1 2 3 不存在的产品10001 与模块1821, 1825, 1831 不存在的模块1000001下的bug
r($bug->getModuleBugsTest($productIDList[0], $moduleIDList[1])) && p('title') && e('BUG4,BUG1'); // 查询产品1 2 3 不存在的产品10001 与模块1821, 1825下的bug
r($bug->getModuleBugsTest($productIDList[0], $moduleIDList[2])) && p('title') && e('0');         // 查询产品1 2 3 不存在的产品10001 与不存在的模块1000001下的bug
r($bug->getModuleBugsTest($productIDList[1], $moduleIDList[0])) && p('title') && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG1');      // 查询产品1 3与模块1821, 1825, 1831 不存在的模块1000001下的bug
r($bug->getModuleBugsTest($productIDList[1], $moduleIDList[1])) && p('title') && e('BUG1');      // 查询产品1 3与模块1821, 1825下的bug
r($bug->getModuleBugsTest($productIDList[1], $moduleIDList[2])) && p('title') && e('0');         // 查询产品1 3与模块不存在的模块1000001下的bug
r($bug->getModuleBugsTest($productIDList[2], $moduleIDList[0])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821, 1825, 1831 不存在的模块1000001下的bug
r($bug->getModuleBugsTest($productIDList[2], $moduleIDList[1])) && p('title') && e('0');         // 查询不存在的产品10001 与模块1821, 1825下的bug
r($bug->getModuleBugsTest($productIDList[2], $moduleIDList[2])) && p('title') && e('0');         // 查询不存在的产品10001 与不存在的模块1000001下的bug