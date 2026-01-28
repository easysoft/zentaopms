#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel->getHostByID().
timeout=0
cid=19834

- 测试当browseType为all时，param为0，并且排序为默认值，返回所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @10
 - 第0条的status属性 @shutoff
 - 第1条的id属性 @9
 - 第1条的status属性 @wait
 - 第9条的id属性 @1
 - 第9条的status属性 @running
- 测试当browseType为all时，param为0，并且排序值id_asc，返回所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @1
 - 第0条的status属性 @running
 - 第1条的id属性 @2
 - 第1条的status属性 @ready
 - 第9条的id属性 @10
 - 第9条的status属性 @shutoff
- 测试当browseType为bysearch时，param为1，并且排序为默认值，返回搜索后的节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @10
 - 第0条的status属性 @shutoff
 - 第1条的id属性 @9
 - 第1条的status属性 @wait
- 测试当browseType为bysearch时，param为1，并且排序为按照id正序，返回搜索后的节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @9
 - 第0条的status属性 @wait
 - 第1条的id属性 @10
 - 第1条的status属性 @shutoff
- 测试当browseType为all时，param为0，并且排序值id_asc，返回页码1所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @1
 - 第0条的status属性 @running
 - 第4条的id属性 @5
 - 第4条的status属性 @running
- 测试当browseType为all时，param为0，并且排序值id_asc，返回页吗1所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @10
 - 第0条的status属性 @shutoff
 - 第4条的id属性 @6
 - 第4条的status属性 @running
- 测试当browseType为bysearch时，param为1，并且排序值id_asc，返回搜索后的页吗1的节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @9
 - 第0条的status属性 @wait
 - 第1条的id属性 @10
 - 第1条的status属性 @shutoff
- 测试当browseType为bysearch时，param为1，并且排序值id_desc，返回搜索后的页吗1的节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @10
 - 第0条的status属性 @shutoff
 - 第1条的id属性 @9
 - 第1条的status属性 @wait
- 测试当browseType为all时，param为0，并且排序值id_asc，返回页码2所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @6
 - 第0条的status属性 @running
 - 第4条的id属性 @10
 - 第4条的status属性 @shutoff
- 测试当browseType为all时，param为0，并且排序值id_desc，返回页码2所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @5
 - 第0条的status属性 @running
 - 第4条的id属性 @1
 - 第4条的status属性 @running
- 测试当browseType为bysearch时，param为1，并且排序值id_asc，返回搜索后的页码2的所有节点,由于第二页没有内容，所以默认显示第一页的内容。
 - 第0条的id属性 @9
 - 第0条的status属性 @wait
 - 第1条的id属性 @10
 - 第1条的status属性 @shutoff

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('host')->loadYaml('host')->gen(10);
zenData('image')->loadYaml('image')->gen(10);
zenData('userquery')->loadYaml('userquery')->gen(1);

$ipList = array(1, 10, 0);

$zanode = new zanodeModelTest();

$browseTypeList = array('all', 'bysearch');
$paramList = array(0, 1);
$orderByList = array('t1.id_asc', 't1.id_desc');
$recTotal = 0;
$recPerPage = 5;
$pageID = 1;

r($zanode->getListByQuery($browseTypeList[0], $paramList[0])) && p('0:id,status;1:id,status;9:id,status') && e('10,shutoff;9,wait;1,running');                     //测试当browseType为all时，param为0，并且排序为默认值，返回所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[0])) && p('0:id,status;1:id,status;9:id,status') && e('1,running;2,ready;10,shutoff');    //测试当browseType为all时，param为0，并且排序值id_asc，返回所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1])) && p('0:id,status;1:id,status') && e('10,shutoff;9,wait');                                                //测试当browseType为bysearch时，param为1，并且排序为默认值，返回搜索后的节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1], $orderByList[0])) && p('0:id,status;1:id,status') && e('9,wait;10,shutoff');                               //测试当browseType为bysearch时，param为1，并且排序为按照id正序，返回搜索后的节点,并且检查节点的状态是否正确。

global $app;
$app->setModuleName('zanode');
$app->setMethodName('browse');
$app->loadClass('pager', true);
$pager = pager::init($recTotal, $recPerPage, $pageID);

r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[0], $pager)) && p('0:id,status;4:id,status') && e('1,running;5,running');  //测试当browseType为all时，param为0，并且排序值id_asc，返回页码1所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[1], $pager)) && p('0:id,status;4:id,status') && e('10,shutoff;6,running');    //测试当browseType为all时，param为0，并且排序值id_asc，返回页吗1所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1], $orderByList[0], $pager)) && p('0:id,status;1:id,status') && e('9,wait;10,shutoff');       //测试当browseType为bysearch时，param为1，并且排序值id_asc，返回搜索后的页吗1的节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1], $orderByList[1], $pager)) && p('0:id,status;1:id,status') && e('10,shutoff;9,wait');       //测试当browseType为bysearch时，param为1，并且排序值id_desc，返回搜索后的页吗1的节点,并且检查节点的状态是否正确。

$pageID = 2;
$pager2 = pager::init($recTotal, $recPerPage, $pageID);
r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[0], $pager2)) && p('0:id,status;4:id,status') && e('6,running;10,shutoff');   //测试当browseType为all时，param为0，并且排序值id_asc，返回页码2所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[1], $pager2)) && p('0:id,status;4:id,status') && e('5,running;1,running');    //测试当browseType为all时，param为0，并且排序值id_desc，返回页码2所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1], $orderByList[0], $pager2)) && p('0:id,status;1:id,status') && e('9,wait;10,shutoff');      //测试当browseType为bysearch时，param为1，并且排序值id_asc，返回搜索后的页码2的所有节点,由于第二页没有内容，所以默认显示第一页的内容。