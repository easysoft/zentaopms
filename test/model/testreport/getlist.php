#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testreport.class.php';
su('admin');

/**

title=测试 testreportModel->getList();
cid=1
pid=1

正常查询 >> 1;user3;1,2,3,4
objectID为空查询 >> 0
objectID不存在查询 >> 0
objectType为空查询 >> 10;9

*/
$objectID   = array('1', '', '1000');
$objectType = array('product', '');

$testreport = new testreportTest();
r($testreport->getListTest($objectID[0], $objectType[0])) && p('1:id;1:owner;1:cases') && e('1;user3;1,2,3,4'); //正常查询
r($testreport->getListTest($objectID[1], $objectType[0])) && p() && e('0');                                     //objectID为空查询
r($testreport->getListTest($objectID[2], $objectType[0])) && p() && e('0');                                     //objectID不存在查询
r($testreport->getListTest($objectID[0], $objectType[1])) && p('10:id;9:id') && e('10;9');                      //objectType为空查询