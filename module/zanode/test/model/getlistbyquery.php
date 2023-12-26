#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->getHostByID().
timeout=0
cid=1

- 测试当browseType为all时，param为0，并且排序为默认值，返回所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @13
 - 第0条的status属性 @shutoff
 - 第1条的id属性 @12
 - 第1条的status属性 @wait
 - 第12条的id属性 @1
 - 第12条的status属性 @running
- 测试当browseType为all时，param为0，并且排序值id_asc，返回所有节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @1
 - 第0条的status属性 @running
 - 第1条的id属性 @2
 - 第1条的status属性 @ready
 - 第12条的id属性 @13
 - 第12条的status属性 @shutoff
- 测试当browseType为bysearch时，param为1，并且排序为默认值，返回搜索后的节点,并且检查节点的状态是否正确。
 - 第0条的id属性 @10
 - 第0条的status属性 @wait
 - 第1条的id属性 @9
 - 第1条的status属性 @wait

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(13);
zdTable('image')->gen(10);
zdTable('userquery')->config('userquery')->gen(1);

$ipList = array(1, 10, 0);

$zanode = new zanodeTest();

$browseTypeList = array('all', 'bysearch');
$paramList = array(0, 1);
$orderByList = array('t1.id_asc');

r($zanode->getListByQuery($browseTypeList[0], $paramList[0])) && p('0:id,status;1:id,status;12:id,status') && e('13,shutoff;12,wait;1,running');                     //测试当browseType为all时，param为0，并且排序为默认值，返回所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[0], $paramList[0], $orderByList[0])) && p('0:id,status;1:id,status;12:id,status') && e('1,running;2,ready;13,shutoff');    //测试当browseType为all时，param为0，并且排序值id_asc，返回所有节点,并且检查节点的状态是否正确。
r($zanode->getListByQuery($browseTypeList[1], $paramList[1])) && p('0:id,status;1:id,status') && e('10,wait;9,wait');                                                //测试当browseType为bysearch时，param为1，并且排序为默认值，返回搜索后的节点,并且检查节点的状态是否正确。