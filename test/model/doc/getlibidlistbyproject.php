#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLibIdListByProject();
cid=1
pid=1

测试未开始项目相关的库id >> 822;823
测试进行中项目相关的库id >> 827;828
测试已暂停项目相关的库id >> 87;97
测试已关闭项目相关的库id >> 88;98

*/

$doc = new docTest();
$t_numProjectID = array('11', '12', '17', '18');

r($doc->getLibIdListByProjectTest($t_numProjectID[0])) && p('1;2')   && e('822;823'); //测试未开始项目相关的库id
r($doc->getLibIdListByProjectTest($t_numProjectID[1])) && p('6;7')   && e('827;828'); //测试进行中项目相关的库id
r($doc->getLibIdListByProjectTest($t_numProjectID[2])) && p('88;89') && e('87;97'); //测试已暂停项目相关的库id
r($doc->getLibIdListByProjectTest($t_numProjectID[3])) && p('88;89') && e('88;98'); //测试已关闭项目相关的库id