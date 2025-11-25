#!/usr/bin/env php
<?php

/**

title=测试 zanodeTao::getZaNodeListByQuery();
timeout=0
cid=19850

- 步骤1：查询所有节点属性count @15
- 步骤2：按状态查询属性count @10
- 步骤3：按主机类型查询属性count @8
- 步骤4：多条件查询属性count @8
- 步骤5：查询不存在条件属性count @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('host');
$table->id->range('1-20');
$table->name->range('node-1, node-2, node-3, node-4, node-5, node-6, node-7, node-8, node-9, node-10, node-11, node-12, node-13, node-14, node-15, host-16, host-17, host-18, host-19, host-20');
$table->type->range('node{15}, normal{5}');
$table->hostType->range('vm{8}, physical{4}, container{3}, normal{5}');
$table->status->range('running{10}, stopped{5}, error{3}, offline{2}');
$table->parent->range('1-5');
$table->image->range('1-10');
$table->deleted->range('0{18}, 1{2}');
$table->gen(20);

// 生成镜像数据
$imageTable = zenData('image');
$imageTable->id->range('1-10');
$imageTable->name->range('ubuntu-latest, centos-7, windows-server, debian-11, alpine-latest, node-14, python-3.9, java-11, nginx-latest, redis-6');
$imageTable->osName->range('Ubuntu{4}, CentOS{3}, Windows{3}');
$imageTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($zanodeTest->getZaNodeListByQueryTest('', 'id_asc')) && p('count') && e('15'); // 步骤1：查询所有节点
r($zanodeTest->getZaNodeListByQueryTest("t1.status='running'", 'id_asc')) && p('count') && e('10'); // 步骤2：按状态查询
r($zanodeTest->getZaNodeListByQueryTest("t1.hostType='vm'", 'id_asc')) && p('count') && e('8'); // 步骤3：按主机类型查询
r($zanodeTest->getZaNodeListByQueryTest("t1.status='running' AND t1.hostType='vm'", 'id_asc')) && p('count') && e('8'); // 步骤4：多条件查询
r($zanodeTest->getZaNodeListByQueryTest("t1.status='nonexistent'", 'id_asc')) && p('count') && e('0'); // 步骤5：查询不存在条件