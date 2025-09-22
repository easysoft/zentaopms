#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getGogsProjects();
timeout=0
cid=0

- 测试步骤1：有效服务器ID查询项目数量 @1
- 测试步骤2：项目详细信息验证
 - 属性id @1
 - 属性name @unittest
- 测试步骤3：服务器ID为0的边界值测试 @0
- 测试步骤4：不存在的服务器ID测试 @0
- 测试步骤5：负数服务器ID测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth', false, 2)->gen(5);

su('admin');

$mrTest = new mrTest();

r($mrTest->getGogsProjectsTester(5))       && p() && e('1');               // 测试步骤1：有效服务器ID查询项目数量
r($mrTest->getGogsProjectsDetailTester(5)) && p('id,name') && e('1,unittest'); // 测试步骤2：项目详细信息验证
r($mrTest->getGogsProjectsTester(0))       && p() && e('0');               // 测试步骤3：服务器ID为0的边界值测试
r($mrTest->getGogsProjectsTester(999))     && p() && e('0');               // 测试步骤4：不存在的服务器ID测试
r($mrTest->getGogsProjectsTester(-1))      && p() && e('0');               // 测试步骤5：负数服务器ID测试