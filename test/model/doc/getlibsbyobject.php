#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getLibsByObject();
cid=1
pid=1

all类型查询 >> 0
api类型查询 >> 0
product类型查询 >> 产品主库;execution
project类型查询 >> 0
execution类型查询 >> 迭代主库;execution
custom类型查询 >> 自定义库;execution
book类型查询 >> execution

*/
global $tester;
$doc = $tester->loadModel('doc');

$types        = array('all', 'api', 'product', 'project', 'execution', 'custom', 'book');
$objectIDList = array('1', '117', '217', '901');
$appendLib    = '701';

r($doc->getLibsByObject($types[0], $objectIDList[0], '', $appendLib)) && p()                    && e('0');                 //all类型查询
r($doc->getLibsByObject($types[1], $objectIDList[0], '', $appendLib)) && p()                    && e('0');                 //api类型查询
r($doc->getLibsByObject($types[2], $objectIDList[0], '', $appendLib)) && p('1:name;701:type')   && e('产品主库;execution');//product类型查询
r($doc->getLibsByObject($types[3], $objectIDList[1], '', $appendLib)) && p()                    && e('0');                 //project类型查询
r($doc->getLibsByObject($types[4], $objectIDList[2], '', $appendLib)) && p('307:name;701:type') && e('迭代主库;execution');//execution类型查询
r($doc->getLibsByObject($types[5], $objectIDList[3], '', $appendLib)) && p('900:name;701:type') && e('自定义库;execution');//custom类型查询
r($doc->getLibsByObject($types[6], $objectIDList[3], '', $appendLib)) && p('701:type')          && e('execution');         //book类型查询