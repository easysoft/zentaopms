#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::getServiceStatus();
timeout=0
cid=0

- 测试步骤1: 正常节点服务状态查询
 - 属性ZenAgent @ready
 - 属性ZTF @ready
- 测试步骤2: ZTF服务离线的节点
 - 属性ZenAgent @ready
 - 属性ZTF @offline
- 测试步骤3: HTTP请求失败的节点
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 测试步骤4: 响应代码非success的节点
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 测试步骤5: 响应缺少ztfStatus的节点
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

su('admin');

$zanodeTest = new zanodeTest();

// 测试场景1: 正常节点,服务都正常运行
$node1 = new stdClass();
$node1->ip = '192.168.1.100';
$node1->zap = '8085';
$node1->tokenSN = 'test-token-1';

// 测试场景2: ZTF服务离线的节点
$node2 = new stdClass();
$node2->ip = '192.168.1.101';
$node2->zap = '8085';
$node2->tokenSN = 'test-token-2';

// 测试场景3: HTTP请求失败(空响应)
$node3 = new stdClass();
$node3->ip = '192.168.1.102';
$node3->zap = '8085';
$node3->tokenSN = 'test-token-3';

// 测试场景4: 响应代码非success
$node4 = new stdClass();
$node4->ip = '192.168.1.103';
$node4->zap = '8085';
$node4->tokenSN = 'test-token-4';

// 测试场景5: 响应缺少ztfStatus
$node5 = new stdClass();
$node5->ip = '192.168.1.104';
$node5->zap = '8085';
$node5->tokenSN = 'test-token-5';

r($zanodeTest->getServiceStatusTest($node1)) && p('ZenAgent,ZTF') && e('ready,ready'); // 测试步骤1: 正常节点服务状态查询
r($zanodeTest->getServiceStatusTest($node2)) && p('ZenAgent,ZTF') && e('ready,offline'); // 测试步骤2: ZTF服务离线的节点
r($zanodeTest->getServiceStatusTest($node3)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤3: HTTP请求失败的节点
r($zanodeTest->getServiceStatusTest($node4)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤4: 响应代码非success的节点
r($zanodeTest->getServiceStatusTest($node5)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 测试步骤5: 响应缺少ztfStatus的节点