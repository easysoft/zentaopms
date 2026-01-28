#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getGogsProjects();
timeout=0
cid=17248

- 测试步骤1：有效服务器ID查询项目数量 @1
- 测试步骤2：项目详细信息验证-id属性id @1
- 测试步骤3：项目详细信息验证-name属性name @unittest
- 测试步骤4：服务器ID为0的边界值测试 @0
- 测试步骤5：不存在的服务器ID测试 @0
- 测试步骤6：负数服务器ID测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mrTest = new mrModelTest();

r($mrTest->getGogsProjectsTester(5)) && p() && e('1');           // 测试步骤1：有效服务器ID查询项目数量
r($mrTest->getGogsProjectsDetailTester(5)) && p('id') && e('1'); // 测试步骤2：项目详细信息验证-id
r($mrTest->getGogsProjectsDetailTester(5)) && p('name') && e('unittest'); // 测试步骤3：项目详细信息验证-name
r($mrTest->getGogsProjectsTester(0)) && p() && e('0');           // 测试步骤4：服务器ID为0的边界值测试
r($mrTest->getGogsProjectsTester(999)) && p() && e('0');         // 测试步骤5：不存在的服务器ID测试
r($mrTest->getGogsProjectsTester(-1)) && p() && e('0');          // 测试步骤6：负数服务器ID测试