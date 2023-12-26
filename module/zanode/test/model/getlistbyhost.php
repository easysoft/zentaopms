#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->getListByHost().
timeout=0
cid=1

- 传入空的hostID，返回值应该为空数组。 @0
- id为2下的所有执行节点数据是否正确，是否按照默认的id排序。
 - 第0条的id属性 @8
 - 第0条的status属性 @online
 - 第2条的id属性 @10
 - 第2条的status属性 @shutoff
 - 第4条的id属性 @12
 - 第4条的status属性 @wait
- id为2下的所有执行节点数据是否正确，是否按照heartbeat降序排序。
 - 第0条的id属性 @12
 - 第0条的status属性 @wait
 - 第2条的id属性 @10
 - 第2条的status属性 @shutoff
 - 第4条的id属性 @8
 - 第4条的status属性 @online

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';
su('admin');

zdTable('host')->config('host')->gen(12);

$zanode = new zanodeTest();

$hostIDList  = array(0, 1, 2);
$orderByList = array('', 'heartbeat_desc');

r($zanode->getListByHost($hostIDList[0], $orderByList[0])) && p('') && e(0);                                                                  //传入空的hostID，返回值应该为空数组。
r($zanode->getListByHost($hostIDList[2], $orderByList[0])) && p('0:id,status;2:id,status;4:id,status') && e('8,online;10,shutoff;12,wait');   //id为2下的所有执行节点数据是否正确，是否按照默认的id排序。
r($zanode->getListByHost($hostIDList[2], $orderByList[1])) && p('0:id,status;2:id,status;4:id,status') && e('12,wait;10,shutoff;8,online');   //id为2下的所有执行节点数据是否正确，是否按照heartbeat降序排序。