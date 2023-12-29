#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->getList();
timeout=0
cid=1

- 测试名称不能为空第name条的0属性 @『名称』不能为空。
- 测试单CPU核心数不能为空第cpuCores条的0属性 @『单CPU核心数』不能为空。
- 测试单CPU核心数应当大于0第cpuCores条的1属性 @『单CPU核心数』应当大于『0』。
- 测试内存大小不能为空第memory条的0属性 @『内存大小』不能为空。
- 测试内存大小应当是数字，可以是小数第memory条的1属性 @『内存大小』应当是数字，可以是小数。
- 测试硬盘容量不能为空第diskSize条的0属性 @『硬盘容量』不能为空。
- 测试硬盘容量应当大于0第diskSize条的1属性 @『硬盘容量』应当大于『0』。
- 测试硬盘容量应当是数字，可以是小数第diskSize条的2属性 @『硬盘容量』应当是数字，可以是小数。
- 测试更新成功
 - 第0条的field属性 @vsoft
 - 第0条的old属性 @~~
 - 第0条的new属性 @kvm

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zahost.class.php';
su('admin');

$host = zdTable('host');
$host->type->range('zahost');
$host->name->range('宿主机1');
$host->gen(1);

$hostInfo = new stdclass();
$hostInfo->id       = 1;
$hostInfo->vsoft    = 'kvm';
$hostInfo->hostType = 'physical';
$hostInfo->name     = '';
$hostInfo->extranet = '10.0.1.222';
$hostInfo->cpuCores = '';
$hostInfo->memory   = '';
$hostInfo->diskSize = '';

$zahost = new zahostTest();
r($zahost->updateTest($hostInfo)) && p('name:0')     && e('『名称』不能为空。');                   //测试名称不能为空
r($zahost->updateTest($hostInfo)) && p('cpuCores:0') && e('『单CPU核心数』不能为空。');            //测试单CPU核心数不能为空
r($zahost->updateTest($hostInfo)) && p('cpuCores:1') && e('『单CPU核心数』应当大于『0』。');       //测试单CPU核心数应当大于0
r($zahost->updateTest($hostInfo)) && p('memory:0')   && e('『内存大小』不能为空。');               //测试内存大小不能为空
r($zahost->updateTest($hostInfo)) && p('memory:1')   && e('『内存大小』应当是数字，可以是小数。'); //测试内存大小应当是数字，可以是小数
r($zahost->updateTest($hostInfo)) && p('diskSize:0') && e('『硬盘容量』不能为空。');               //测试硬盘容量不能为空
r($zahost->updateTest($hostInfo)) && p('diskSize:1') && e('『硬盘容量』应当大于『0』。');          //测试硬盘容量应当大于0
r($zahost->updateTest($hostInfo)) && p('diskSize:2') && e('『硬盘容量』应当是数字，可以是小数。'); //测试硬盘容量应当是数字，可以是小数

$hostInfo->name     = '宿主机1';
$hostInfo->cpuCores = '1';
$hostInfo->memory   = 256;
$hostInfo->diskSize = 256;
r($zahost->updateTest($hostInfo)) && p('0:field,old,new') && e('vsoft,~~,kvm'); //测试更新成功