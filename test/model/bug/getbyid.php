#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getById();
cid=1
pid=1

查询id为1的bug的title >> BUG1
查询id为1的bug的status >> active
查询id为1的bug的plan >> 1
查询id为1的bug的module >> 1821
查询id为1的bug的story >> 2
查询id为4的bug的title >> BUG4
查询id为4的bug的status >> active
查询id为4的bug的plan >> 4
查询id为4的bug的module >> 1825
查询id为4的bug的story >> 14
查询id为7的bug的title >> 缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7
查询id为7的bug的status >> active
查询id为7的bug的plan >> 7
查询id为7的bug的module >> 1831
查询id为7的bug的story >> 26
查询id为不存在的1000001的bug的title >> 0
查询id为不存在的1000001的bug的status >> 0
查询id为不存在的1000001的bug的plan >> 0
查询id为不存在的1000001的bug的module >> 0
查询id为不存在的1000001的bug的story >> 0

*/

$bugIDList = array('1', '4', '7', '1000001');

$bug=new bugTest();

r($bug->getByIdTest($bugIDList[0])) && p('title')  && e('BUG1'); // 查询id为1的bug的title
r($bug->getByIdTest($bugIDList[0])) && p('status') && e('active'); // 查询id为1的bug的status
r($bug->getByIdTest($bugIDList[0])) && p('plan')   && e('1'); // 查询id为1的bug的plan
r($bug->getByIdTest($bugIDList[0])) && p('module') && e('1821'); // 查询id为1的bug的module
r($bug->getByIdTest($bugIDList[0])) && p('story')  && e('2'); // 查询id为1的bug的story
r($bug->getByIdTest($bugIDList[1])) && p('title')  && e('BUG4'); // 查询id为4的bug的title
r($bug->getByIdTest($bugIDList[1])) && p('status') && e('active'); // 查询id为4的bug的status
r($bug->getByIdTest($bugIDList[1])) && p('plan')   && e('4'); // 查询id为4的bug的plan
r($bug->getByIdTest($bugIDList[1])) && p('module') && e('1825'); // 查询id为4的bug的module
r($bug->getByIdTest($bugIDList[1])) && p('story')  && e('14'); // 查询id为4的bug的story
r($bug->getByIdTest($bugIDList[2])) && p('title')  && e('缺陷!@()(){}|+=%^&*$#测试bug名称到底可以有多长！@#￥%&*":.<>。?/（）;7'); // 查询id为7的bug的title
r($bug->getByIdTest($bugIDList[2])) && p('status') && e('active'); // 查询id为7的bug的status
r($bug->getByIdTest($bugIDList[2])) && p('plan')   && e('7'); // 查询id为7的bug的plan
r($bug->getByIdTest($bugIDList[2])) && p('module') && e('1831'); // 查询id为7的bug的module
r($bug->getByIdTest($bugIDList[2])) && p('story')  && e('26'); // 查询id为7的bug的story
r($bug->getByIdTest($bugIDList[3])) && p('title')  && e('0'); // 查询id为不存在的1000001的bug的title
r($bug->getByIdTest($bugIDList[3])) && p('status') && e('0'); // 查询id为不存在的1000001的bug的status
r($bug->getByIdTest($bugIDList[3])) && p('plan')   && e('0'); // 查询id为不存在的1000001的bug的plan
r($bug->getByIdTest($bugIDList[3])) && p('module') && e('0'); // 查询id为不存在的1000001的bug的module
r($bug->getByIdTest($bugIDList[3])) && p('story')  && e('0'); // 查询id为不存在的1000001的bug的story