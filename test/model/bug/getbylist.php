#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByList();
cid=1
pid=1

查询id为1的bug title >> BUG1
查询id为2的bug status >> active
查询id为3的bug title >> BUG3
查询id为1的bug title >> BUG1
查询id为4的bug title >> BUG4
查询id为4的bug status >> active
查询id为1的bug title >> BUG1
查询id为1的bug title >> BUG1
查询id为2的bug title >> BUG2
查询id为2的bug status >> active
查询id为7的bug title >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询id为7的bug title >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7

*/

$bugIDList  = array('1,2,3', '1,4', '2,7', '1000001');
$fieldsList = array('*', 'id,title,status,plan,module,story', 'title,status', 'title');

$bug=new bugTest();

r($bug->getByListTest($bugIDList[0], $fieldsList[0])) && p('1:title')  && e('BUG1');   // 查询id为1的bug title
r($bug->getByListTest($bugIDList[0], $fieldsList[1])) && p('2:status') && e('active'); // 查询id为2的bug status
r($bug->getByListTest($bugIDList[0], $fieldsList[2])) && p('3:title')  && e('BUG3');   // 查询id为3的bug title
r($bug->getByListTest($bugIDList[0], $fieldsList[3])) && p('1:title')  && e('BUG1');   // 查询id为1的bug title
r($bug->getByListTest($bugIDList[1], $fieldsList[0])) && p('4:title')  && e('BUG4');   // 查询id为4的bug title
r($bug->getByListTest($bugIDList[1], $fieldsList[1])) && p('4:status') && e('active'); // 查询id为4的bug status
r($bug->getByListTest($bugIDList[1], $fieldsList[2])) && p('1:title')  && e('BUG1');   // 查询id为1的bug title
r($bug->getByListTest($bugIDList[1], $fieldsList[3])) && p('1:title')  && e('BUG1');   // 查询id为1的bug title
r($bug->getByListTest($bugIDList[2], $fieldsList[0])) && p('2:title')  && e('BUG2');   // 查询id为2的bug title
r($bug->getByListTest($bugIDList[2], $fieldsList[1])) && p('2:status') && e('active'); // 查询id为2的bug status
r($bug->getByListTest($bugIDList[2], $fieldsList[2])) && p('7:title')  && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询id为7的bug title
r($bug->getByListTest($bugIDList[2], $fieldsList[3])) && p('7:title')  && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询id为7的bug title