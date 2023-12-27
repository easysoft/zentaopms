#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getList();
timeout=0
cid=1

- 查询所有的宿主机中第一个宿主机
 - 第0条的hostID属性 @2
 - 第0条的name属性 @宿主机2
- 查询所有的宿主机中第二个宿主机
 - 第1条的hostID属性 @1
 - 第1条的name属性 @宿主机1
- 搜索标题中包含1的宿主机
 - 第1条的hostID属性 @1
 - 第1条的name属性 @宿主机1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->id->range('1-3');
$host->type->range('zahost');
$host->name->range('宿主机1,宿主机2,宿主机3');
$host->deleted->range('0{2},1');
$host->gen(3);

$userquery = zdTable('userquery');
$userquery->module->range('zahost');
$userquery->sql->range("(( 1  AND `name`  LIKE '%1%' ) AND ( 1  ))");
$userquery->gen(1);

$browseTypeList = array('all', 'browseType');
$paramList      = array(0, 1);

$zahost = new zahostTest();
r($zahost->getListTest($browseTypeList[0], $paramList[0])) && p('0:hostID,name') && e('2,宿主机2');  //查询所有的宿主机中第一个宿主机
r($zahost->getListTest($browseTypeList[0], $paramList[0])) && p('1:hostID,name') && e('1,宿主机1');  //查询所有的宿主机中第二个宿主机
r($zahost->getListTest($browseTypeList[1], $paramList[1])) && p('1:hostID,name') && e('1,宿主机1');  //搜索标题中包含1的宿主机