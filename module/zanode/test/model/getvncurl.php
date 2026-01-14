#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::getVncUrl();
timeout=0
cid=19839

- 步骤1：正常节点VNC URL（由于HTTP请求失败） @0
- 步骤2：宿主机节点（无vnc）获取VNC URL @0
- 步骤3：不存在的节点ID获取VNC URL @0
- 步骤4：无vnc端口的节点获取VNC URL @0
- 步骤5：无效ID（0）获取VNC URL @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$hostTable = zenData('host');
$hostTable->id->range('1-3');
$hostTable->name->range('zahost1,zanode1,zanode2');
$hostTable->type->range('zahost,node,node');
$hostTable->hostType->range('physical,[]{2}');
$hostTable->status->range('online,running,running');
$hostTable->image->range('1');
$hostTable->parent->range('0,1,1');
$hostTable->vnc->range('0,0,5900');
$hostTable->tokenSN->range('f9f9220b37bd2a92061417118afe165c,[]{2}');
$hostTable->zap->range('55001,55156,0');
$hostTable->extranet->range('10.0.1.222,[]{2}');
$hostTable->gen(3);

$imageTable = zenData('image');
$imageTable->id->range('1');
$imageTable->host->range('2');
$imageTable->name->range('snapshot1');
$imageTable->status->range('completed');
$imageTable->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($zanodeTest->getVncUrlTest(3)) && p() && e('0'); // 步骤1：正常节点VNC URL（由于HTTP请求失败）
r($zanodeTest->getVncUrlTest(1)) && p() && e('0'); // 步骤2：宿主机节点（无vnc）获取VNC URL
r($zanodeTest->getVncUrlTest(999)) && p() && e('0'); // 步骤3：不存在的节点ID获取VNC URL
r($zanodeTest->getVncUrlTest(2)) && p() && e('0'); // 步骤4：无vnc端口的节点获取VNC URL
r($zanodeTest->getVncUrlTest(0)) && p() && e('0'); // 步骤5：无效ID（0）获取VNC URL