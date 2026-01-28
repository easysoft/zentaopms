#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createBuild();
timeout=0
cid=15832

- 步骤1：正常情况
 - 属性product @1
 - 属性project @1
 - 属性system @1
 - 属性name @v1.0.0
- 步骤2：关联数据
 - 属性product @2
 - 属性project @2
 - 属性system @1
 - 属性name @v2.0.0
- 步骤3：空名称
 - 属性product @1
 - 属性project @1
 - 属性system @1
- 步骤4：无效产品ID
 - 属性product @0
 - 属性project @1
 - 属性system @1
- 步骤5：版本组为空
 - 属性product @3
 - 属性project @3
 - 属性system @2
 - 属性name @v4.0.0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$buildTable = zenData('build');
$buildTable->product->range('1-3');
$buildTable->project->range('1-5');
$buildTable->system->range('1-2');
$buildTable->name->range('Build{5}, Version{3}, Release{2}');
$buildTable->builder->range('admin{5}, user1{3}, user2{2}');
$buildTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 准备测试数据
$data1 = new stdclass();
$data1->id = 1;
$data1->vname = 'v1.0.0';
$data1->releasedate = '2024-01-15 10:00:00';

$data2 = new stdclass();
$data2->id = 2;
$data2->vname = 'v2.0.0';
$data2->releasedate = '2024-06-01 15:30:00';

$data3 = new stdclass();
$data3->id = 3;
$data3->vname = '';
$data3->releasedate = '';

$data4 = new stdclass();
$data4->id = 4;
$data4->vname = 'v3.0.0';
$data4->releasedate = '2024-12-31 23:59:59';

$data5 = new stdclass();
$data5->id = 5;
$data5->vname = 'v4.0.0';
$data5->releasedate = '2024-03-15 12:00:00';

// 版本组数据
$versionGroup1 = array(
    1 => array(
        (object)array('issueid' => 1001, 'relation' => 'IssueFixVersion'),
        (object)array('issueid' => 1002, 'relation' => 'IssueVersion')
    )
);

$issueList1 = array(
    1001 => array('BID' => 1, 'BType' => 'zstory'),
    1002 => array('BID' => 2, 'BType' => 'zbug')
);

$versionGroup2 = array(
    1 => array(
        (object)array('issueid' => 1003, 'relation' => 'IssueFixVersion'),
        (object)array('issueid' => 1004, 'relation' => 'IssueFixVersion')
    )
);

$issueList2 = array(
    1003 => array('BID' => 3, 'BType' => 'zstory'),
    1004 => array('BID' => 4, 'BType' => 'zbug')
);

// 6. 强制要求：必须包含至少5个测试步骤
r($convertTest->createBuildTest(1, 1, 1, $data1, $versionGroup1, $issueList1)) && p('product,project,system,name') && e('1,1,1,v1.0.0'); // 步骤1：正常情况
r($convertTest->createBuildTest(2, 2, 1, $data2, $versionGroup2, $issueList2)) && p('product,project,system,name') && e('2,2,1,v2.0.0'); // 步骤2：关联数据
r($convertTest->createBuildTest(1, 1, 1, $data3, array(), array())) && p('product,project,system') && e('1,1,1'); // 步骤3：空名称
r($convertTest->createBuildTest(0, 1, 1, $data4, array(), array())) && p('product,project,system') && e('0,1,1'); // 步骤4：无效产品ID
r($convertTest->createBuildTest(3, 3, 2, $data5, array(), array())) && p('product,project,system,name') && e('3,3,2,v4.0.0'); // 步骤5：版本组为空