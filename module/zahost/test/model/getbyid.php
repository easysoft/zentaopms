#!/usr/bin/env php
<?php

/**

title=测试 zahostModel::getByID();
timeout=0
cid=19744

- 执行zahost模块的getByIDTest方法，参数是1
 - 属性hostID @1
 - 属性name @宿主机1
 - 属性type @zahost
 - 属性status @offline
- 执行zahost模块的getByIDTest方法，参数是999  @0
- 执行zahost模块的getByIDTest方法  @0
- 执行zahost模块的getByIDTest方法，参数是2 属性status @offline
- 执行zahost模块的getByIDTest方法，参数是3 属性heartbeat @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$host = zenData('host');
$host->id->range('1-3');
$host->type->range('zahost');
$host->name->range('宿主机1,宿主机2,宿主机3');
$host->status->range('online');
$host->extranet->range('192.168.1.100,192.168.1.101,192.168.1.102');
$host->memory->range('8G,16G,32G');
$host->cpuCores->range('4,8,16');
$host->deleted->range('0');
$host->gen(3);

global $tester;
$tester->dao->update(TABLE_ZAHOST)->set('heartbeat')->eq('2024-01-01 10:00:00')->where('id')->eq(1)->exec();
$tester->dao->update(TABLE_ZAHOST)->set('heartbeat')->eq(date('Y-m-d H:i:s', time() - 120))->where('id')->eq(2)->exec();
$tester->dao->update(TABLE_ZAHOST)->set('heartbeat')->eq(null)->where('id')->eq(3)->exec();

su('admin');

$zahost = new zahostModelTest();

r($zahost->getByIDTest(1)) && p('hostID,name,type,status') && e('1,宿主机1,zahost,offline');
r($zahost->getByIDTest(999)) && p() && e('0');
r($zahost->getByIDTest(0)) && p() && e('0');
r($zahost->getByIDTest(2)) && p('status') && e('offline');
r($zahost->getByIDTest(3)) && p('heartbeat') && e('~~');