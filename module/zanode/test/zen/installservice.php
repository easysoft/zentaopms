#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::installService();
timeout=0
cid=0

- 测试步骤1: 在正常节点上安装ZTF服务
 - 属性ZenAgent @ready
 - 属性ZTF @ready
- 测试步骤2: 在正常节点上安装zendata服务
 - 属性ZenAgent @ready
 - 属性ZTF @ready
- 测试步骤3: 在HTTP请求失败的节点上安装服务
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 测试步骤4: 在返回非成功状态码的节点上安装服务
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 测试步骤5: 在返回数据缺失的节点上安装服务
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 测试步骤6: 测试服务名称大小写不敏感处理
 - 属性ZenAgent @ready
 - 属性ZTF @ready

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

zenData('host')->gen(0);

su('admin');

$zanodeTest = new zanodeTest();

// 准备测试数据: 创建不同场景的节点
$host = zenData('host');
$host->id->range('1-10');
$host->name->range('node1,node2,node3,node4,node5');
$host->extranet->range('192.168.1.100,192.168.1.101,192.168.1.102,192.168.1.103,192.168.1.104');
$host->zap->range('8085');
$host->tokenSN->range('token1,token2,token3,token4,token5');
$host->secret->range('secret1,secret2,secret3,secret4,secret5');
$host->status->range('running');
$host->type->range('node');
$host->gen(5);

// 创建节点对象
$node1 = new stdClass();
$node1->id = 1;
$node1->ip = '192.168.1.100';
$node1->zap = '8085';
$node1->tokenSN = 'token1';
$node1->secret = 'secret1';

$node2 = new stdClass();
$node2->id = 2;
$node2->ip = '192.168.1.101';
$node2->zap = '8085';
$node2->tokenSN = 'token2';
$node2->secret = 'secret2';

$node3 = new stdClass();
$node3->id = 3;
$node3->ip = '192.168.1.102';
$node3->zap = '8085';
$node3->tokenSN = 'token3';
$node3->secret = 'secret3';

$node4 = new stdClass();
$node4->id = 4;
$node4->ip = '192.168.1.103';
$node4->zap = '8085';
$node4->tokenSN = 'token4';
$node4->secret = 'secret4';

$node5 = new stdClass();
$node5->id = 5;
$node5->ip = '192.168.1.104';
$node5->zap = '8085';
$node5->tokenSN = 'token5';
$node5->secret = 'secret5';

r($zanodeTest->installServiceTest($node1, 'ztf')) && p('ZenAgent,ZTF') && e('ready,ready'); // 测试步骤1: 在正常节点上安装ZTF服务
r($zanodeTest->installServiceTest($node1, 'zendata')) && p('ZenAgent,ZTF') && e('ready,ready'); // 测试步骤2: 在正常节点上安装zendata服务
r($zanodeTest->installServiceTest($node2, 'ztf')) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤3: 在HTTP请求失败的节点上安装服务
r($zanodeTest->installServiceTest($node3, 'ztf')) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤4: 在返回非成功状态码的节点上安装服务
r($zanodeTest->installServiceTest($node4, 'ztf')) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤5: 在返回数据缺失的节点上安装服务
r($zanodeTest->installServiceTest($node5, 'ZTF')) && p('ZenAgent,ZTF') && e('ready,ready'); // 测试步骤6: 测试服务名称大小写不敏感处理