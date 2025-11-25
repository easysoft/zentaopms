#!/usr/bin/env php
<?php

/**

title=测试 zanodeTao::getSubZahostListByID();
timeout=0
cid=19849

- 步骤1：正常主机ID(4)获取子主机，应返回3个子主机属性count @3
- 步骤2：不存在主机ID获取子主机，应返回空数组属性count @0
- 步骤3：主机ID(0)获取子主机，实际有1个主机parent=0属性count @1
- 步骤4：负数主机ID获取子主机，应返回空数组属性count @0
- 步骤5：指定排序方式获取子主机，应返回3个子主机属性count @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. zendata数据准备
$host = zenData('host');
$host->id->range('1-10');
$host->name->range('host1,host2,host3,parent-host,child1,child2,child3,child4,child5,host10');
$host->type->range('zahost{5},node{5}');
$host->parent->range('999{1},998{1},997{1},0{1},4{3},996{3}');
$host->status->range('online{8},offline{2}');
$host->deleted->range('0{10}');
$host->vnc->range('5901-5910');
$host->cpuCores->range('2{2},4{4},8{4}');
$host->memory->range('4G{2},8G{4},16G{4}');
$host->diskSize->range('100G{2},500G{4},1T{4}');
$host->osName->range('Ubuntu{5},CentOS{5}');
$host->heartbeat->range('`2024-09-13 10:00:00`');
$host->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$zanodeTest = new zanodeTest();

// 5. 执行测试步骤 (必须包含至少5个测试步骤)
r($zanodeTest->getSubZahostListByIDTest(4)) && p('count') && e('3');                    // 步骤1：正常主机ID(4)获取子主机，应返回3个子主机
r($zanodeTest->getSubZahostListByIDTest(1000)) && p('count') && e('0');                 // 步骤2：不存在主机ID获取子主机，应返回空数组
r($zanodeTest->getSubZahostListByIDTest(0)) && p('count') && e('1');                    // 步骤3：主机ID(0)获取子主机，实际有1个主机parent=0
r($zanodeTest->getSubZahostListByIDTest(-1)) && p('count') && e('0');                   // 步骤4：负数主机ID获取子主机，应返回空数组
r($zanodeTest->getSubZahostListByIDTest(4, 'name_asc')) && p('count') && e('3');         // 步骤5：指定排序方式获取子主机，应返回3个子主机