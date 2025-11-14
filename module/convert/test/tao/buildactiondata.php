#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildActionData();
timeout=0
cid=15799

- 执行convertTest模块的buildActionDataTest方法，参数是$fullData
 - 属性id @1
 - 属性issueid @100
 - 属性actionbody @Test action body
 - 属性author @admin
 - 属性created @2023-01-01 10:00:00
- 执行convertTest模块的buildActionDataTest方法，参数是$mandatoryData
 - 属性id @2
 - 属性issueid @200
- 执行convertTest模块的buildActionDataTest方法，参数是$mixedData
 - 属性id @5
 - 属性issueid @500
 - 属性author @test
- 执行convertTest模块的buildActionDataTest方法，参数是$bodyData
 - 属性id @3
 - 属性issueid @300
 - 属性actionbody @Partial data
- 执行convertTest模块的buildActionDataTest方法，参数是$stringData
 - 属性id @abc123
 - 属性issueid @issue456
 - 属性actionbody @String ID test

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整action数据构建
$fullData = array(
    'id' => 1,
    'issue' => 100,
    'body' => 'Test action body',
    'author' => 'admin',
    'created' => '2023-01-01 10:00:00'
);
r($convertTest->buildActionDataTest($fullData)) && p('id,issueid,actionbody,author,created') && e('1,100,Test action body,admin,2023-01-01 10:00:00');

// 步骤2：仅必填字段id和issue
$mandatoryData = array(
    'id' => 2,
    'issue' => 200
);
r($convertTest->buildActionDataTest($mandatoryData)) && p('id,issueid') && e('2,200');

// 步骤3：包含id和issue以及部分可选字段
$mixedData = array(
    'id' => 5,
    'issue' => 500,
    'author' => 'test'
);
r($convertTest->buildActionDataTest($mixedData)) && p('id,issueid,author') && e('5,500,test');

// 步骤4：包含body字段
$bodyData = array(
    'id' => 3,
    'issue' => 300,
    'body' => 'Partial data'
);
r($convertTest->buildActionDataTest($bodyData)) && p('id,issueid,actionbody') && e('3,300,Partial data');

// 步骤5：字符串类型的id和issue测试
$stringData = array(
    'id' => 'abc123',
    'issue' => 'issue456',
    'body' => 'String ID test',
    'author' => 'tester',
    'created' => '2023-12-01 15:30:00'
);
r($convertTest->buildActionDataTest($stringData)) && p('id,issueid,actionbody') && e('abc123,issue456,String ID test');