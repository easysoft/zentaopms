#!/usr/bin/env php
<?php
error_reporting(E_ALL & ~E_DEPRECATED);

/**

title=测试 zanodeZen::getServiceStatus();
timeout=0
cid=0

- 步骤1：正常情况，服务全部就绪
 - 属性ZenAgent @ready
 - 属性ZTF @ready
- 步骤2：部分服务离线
 - 属性ZenAgent @ready
 - 属性ZTF @offline
- 步骤3：HTTP请求失败
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 步骤4：API返回码不正确
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install
- 步骤5：缺少ztfStatus字段
 - 属性ZenAgent @not_install
 - 属性ZTF @not_install

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('host');
$table->id->range('1-5');
$table->name->range('node1,node2,node3,node4,node5');
$table->type->range('node{5}');
$table->status->range('running{5}');
$table->extranet->range('192.168.1.100,192.168.1.101,192.168.1.102,192.168.1.103,192.168.1.104');
$table->zap->range('8085{5}');
$table->tokenSN->range('token1,token2,token3,token4,token5');
$table->parent->range('0{5}');
$table->hostType->range('kvm{5}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$zanodeTest = new zanodeTest();

// 5. 创建测试节点对象
$node1 = new stdClass();
$node1->ip = '192.168.1.100';
$node1->zap = '8085';
$node1->tokenSN = 'token1';

$node2 = new stdClass();
$node2->ip = '192.168.1.101';
$node2->zap = '8085';
$node2->tokenSN = 'token2';

$node3 = new stdClass();
$node3->ip = '192.168.1.102';
$node3->zap = '8085';
$node3->tokenSN = 'token3';

$node4 = new stdClass();
$node4->ip = '192.168.1.103';
$node4->zap = '8085';
$node4->tokenSN = 'token4';

$node5 = new stdClass();
$node5->ip = '192.168.1.104';
$node5->zap = '8085';
$node5->tokenSN = 'token5';

// 6. 测试步骤（必须包含至少5个）
r($zanodeTest->getServiceStatusTest($node1)) && p('ZenAgent,ZTF') && e('ready,ready'); // 步骤1：正常情况，服务全部就绪
r($zanodeTest->getServiceStatusTest($node2)) && p('ZenAgent,ZTF') && e('ready,offline'); // 步骤2：部分服务离线
r($zanodeTest->getServiceStatusTest($node3)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 步骤3：HTTP请求失败
r($zanodeTest->getServiceStatusTest($node4)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 步骤4：API返回码不正确
r($zanodeTest->getServiceStatusTest($node5)) && p('ZenAgent,ZTF') && e('not_install,not_install'); // 步骤5：缺少ztfStatus字段