#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getList();
timeout=0
cid=1

- 宿主机1 下的执行节点1第4条的name属性 @执行节点1
- 宿主机2 下的执行节点2第5条的name属性 @执行节点2
- 宿主机3 下没有执行节点 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->id->range('1-5');
$host->type->range('zahost{3},node{2}');
$host->name->range('宿主机1,宿主机2,宿主机3,执行节点1,执行节点2');
$host->parent->range('0{3},1,2');
$host->gen(5);

$hostID = array(1, 2, 3);
$zahost = new zahostTest();
r($zahost->getNodeGroupHostTest($hostID[0])) && p('4:name') && e('执行节点1'); //宿主机1 下的执行节点1
r($zahost->getNodeGroupHostTest($hostID[1])) && p('5:name') && e('执行节点2'); //宿主机2 下的执行节点2
r($zahost->getNodeGroupHostTest($hostID[2])) && p('')       && e('0');         //宿主机3 下没有执行节点
