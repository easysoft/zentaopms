#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getPairs();
timeout=0
cid=1

- 查询宿主机的键值对
 - 属性1 @宿主机1
 - 属性2 @宿主机2
 - 属性3 @~~

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

$zahost = new zahostTest();
r($zahost->getPairsTest()) && p('1,2,3') && e('宿主机1,宿主机2,~~');  //查询宿主机的键值对