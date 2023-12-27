#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getByID();
timeout=0
cid=1

- 查询 ID 为 1 的主机
 - 属性id @1
 - 属性name @宿主机1
 - 属性type @zahost
- 查询 ID 为 2 不存在的主机 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->type->range('zahost');
$host->name->range('宿主机1');
$host->gen(1);

$zahost = new zahostTest();

$hostIDList = array('1', '2');

r($zahost->getByIDTest($hostIDList[0])) && p('id,name,type') && e('1,宿主机1,zahost');  //查询 ID 为 1 的主机
r($zahost->getByIDTest($hostIDList[1])) && p('') && e('~~');                             //查询 ID 为 2 不存在的主机