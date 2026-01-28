#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createProject();
timeout=0
cid=15842

- 步骤1:正常情况,完整的项目数据,无团队成员
 - 属性name @测试项目1
 - 属性code @TEST1
 - 属性status @doing
 - 属性type @project
- 步骤2:包含团队成员的项目创建
 - 属性name @测试项目2
 - 属性code @TEST2
 - 属性status @wait
- 步骤3:缺少description字段的项目
 - 属性name @测试项目3
 - 属性code @TEST3
- 步骤4:缺少created字段的项目
 - 属性name @测试项目4
 - 属性code @TEST4
 - 属性status @wait
- 步骤5:缺少lead字段的项目
 - 属性name @测试项目5
 - 属性code @TEST5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

// 测试步骤1:正常情况,完整的项目数据,无团队成员
$data1 = new stdclass();
$data1->id = 1001;
$data1->pname = '测试项目1';
$data1->pkey = 'TEST1';
$data1->description = '这是一个测试项目';
$data1->status = 'doing';
$data1->created = '2024-01-01 10:00:00';
$data1->lead = 'admin';
r($convertTest->createProjectTest($data1, array())) && p('name,code,status,type') && e('测试项目1,TEST1,doing,project'); // 步骤1:正常情况,完整的项目数据,无团队成员

// 测试步骤2:包含团队成员的项目创建
$data2 = new stdclass();
$data2->id = 1002;
$data2->pname = '测试项目2';
$data2->pkey = 'TEST2';
$data2->description = '包含团队成员的项目';
$data2->status = 'wait';
$data2->created = '2024-02-01 10:00:00';
$data2->lead = 'admin';
r($convertTest->createProjectTest($data2, array(1002 => array('user1', 'user2')))) && p('name,code,status') && e('测试项目2,TEST2,wait'); // 步骤2:包含团队成员的项目创建

// 测试步骤3:缺少description字段的项目
$data3 = new stdclass();
$data3->id = 1003;
$data3->pname = '测试项目3';
$data3->pkey = 'TEST3';
$data3->status = 'done';
$data3->created = '2024-03-01 10:00:00';
$data3->lead = 'admin';
r($convertTest->createProjectTest($data3, array())) && p('name,code') && e('测试项目3,TEST3'); // 步骤3:缺少description字段的项目

// 测试步骤4:缺少created字段的项目
$data4 = new stdclass();
$data4->id = 1004;
$data4->pname = '测试项目4';
$data4->pkey = 'TEST4';
$data4->description = '缺少创建时间字段';
$data4->status = 'wait';
$data4->lead = 'admin';
r($convertTest->createProjectTest($data4, array())) && p('name,code,status') && e('测试项目4,TEST4,wait'); // 步骤4:缺少created字段的项目

// 测试步骤5:缺少lead字段的项目
$data5 = new stdclass();
$data5->id = 1005;
$data5->pname = '测试项目5';
$data5->pkey = 'TEST5';
$data5->description = '缺少负责人字段';
$data5->status = 'doing';
$data5->created = '2024-05-01 10:00:00';
r($convertTest->createProjectTest($data5, array())) && p('name,code') && e('测试项目5,TEST5'); // 步骤5:缺少lead字段的项目