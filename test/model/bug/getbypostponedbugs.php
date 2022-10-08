#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->getByPostponedbugs();
cid=1
pid=1

查询产品25 32 48 100001下被延期的bug >> BUG94,BUG75,BUG74,BUG73
查询产品25下被延期的bug >> BUG75,BUG74,BUG73
查询产品32下被延期的bug >> BUG94
查询产品1下被延期的bug >> 0
查询不存在的产品10001下被延期的bug >> 0

*/

$productIDList = array('25,32,48,1000001', '25' , '32', '1', '1000001');

$bug=new bugTest();

r($bug->getByPostponedBugsTest($productIDList[0])) && p('title') && e('BUG94,BUG75,BUG74,BUG73'); // 查询产品25 32 48 100001下被延期的bug
r($bug->getByPostponedBugsTest($productIDList[1])) && p('title') && e('BUG75,BUG74,BUG73');       // 查询产品25下被延期的bug
r($bug->getByPostponedBugsTest($productIDList[2])) && p('title') && e('BUG94');                   // 查询产品32下被延期的bug
r($bug->getByPostponedBugsTest($productIDList[3])) && p('title') && e('0');                       // 查询产品1下被延期的bug
r($bug->getByPostponedBugsTest($productIDList[4])) && p('title') && e('0');                       // 查询不存在的产品10001下被延期的bug