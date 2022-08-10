#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByLonglifebugs();
cid=1
pid=1

查询产品1 2 3 不存在的产品10001 与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug >> bug8,BUG4,BUG1
查询产品1 2 3 不存在的产品10001 与模块1821, 1825下长时间未关闭的bug >> BUG4,BUG1
查询产品1 2 3 不存在的产品10001 与不存在的模块1000001下长时间未关闭的bug >> 0
查询产品1 2 3 不存在的产品10001下长时间未关闭的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG2,BUG1
查询产品1 3与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug >> bug8,BUG1
查询产品1 3与模块1821, 1825下长时间未关闭的bug >> BUG1
查询产品1 3与模块不存在的模块1000001下长时间未关闭的bug >> 0
查询产品13 不存在的产品10001下长时间未关闭的bug >> BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG2,BUG1
查询不存在的产品10001 与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug >> 0
查询不存在的产品10001 与模块1821, 1825下长时间未关闭的bug >> 0
查询不存在的产品10001 与不存在的模块1000001下长时间未关闭的bug >> 0
查询不存在的产品10001下长时间未关闭的bug >> 0

*/

$productIDList = array('1,2,3,1000001', '1,3', '1000001');
$moduleIDList  = array('1821,1825,1832,1000001', '1821, 1825', '1000001', '0');

$bug=new bugTest();

r($bug->getByLonglifebugsTest($productIDList[0], $moduleIDList[0])) && p('title') && e('bug8,BUG4,BUG1'); // 查询产品1 2 3 不存在的产品10001 与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[0], $moduleIDList[1])) && p('title') && e('BUG4,BUG1');      // 查询产品1 2 3 不存在的产品10001 与模块1821, 1825下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[0], $moduleIDList[2])) && p('title') && e('0');              // 查询产品1 2 3 不存在的产品10001 与不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[0], $moduleIDList[3])) && p('title') && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG6,BUG5,BUG4,BUG3,BUG2,BUG1'); // 查询产品1 2 3 不存在的产品10001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[1], $moduleIDList[0])) && p('title') && e('bug8,BUG1');      // 查询产品1 3与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[1], $moduleIDList[1])) && p('title') && e('BUG1');           // 查询产品1 3与模块1821, 1825下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[1], $moduleIDList[2])) && p('title') && e('0');              // 查询产品1 3与模块不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[1], $moduleIDList[3])) && p('title') && e('BUG9,bug8,缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7,BUG3,BUG2,BUG1');                // 查询产品13 不存在的产品10001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[2], $moduleIDList[0])) && p('title') && e('0');              // 查询不存在的产品10001 与模块1821, 1825, 1832 不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[2], $moduleIDList[1])) && p('title') && e('0');              // 查询不存在的产品10001 与模块1821, 1825下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[2], $moduleIDList[2])) && p('title') && e('0');              // 查询不存在的产品10001 与不存在的模块1000001下长时间未关闭的bug
r($bug->getByLonglifebugsTest($productIDList[2], $moduleIDList[3])) && p('title') && e('0');              // 查询不存在的产品10001下长时间未关闭的bug