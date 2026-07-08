#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getGiteaProjects();
timeout=0
cid=17246

- 测试步骤1：有效的服务器ID获取项目列表 @array
- 测试步骤2：服务器ID为0的边界值测试 @0
- 测试步骤3：负数服务器ID测试 @0
- 测试步骤4：不存在的服务器ID测试 @0
- 测试步骤5：验证返回数据格式为数组 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mrTest = new mrModelTest();

r($mrTest->getGiteaProjectsTester(1))   && p()  && e('array'); // 测试步骤1：有效的服务器ID获取项目列表
r($mrTest->getGiteaProjectsTester(0))   && p()  && e('0');     // 测试步骤2：服务器ID为0的边界值测试
r($mrTest->getGiteaProjectsTester(-1))  && p()  && e('0');     // 测试步骤3：负数服务器ID测试
r($mrTest->getGiteaProjectsTester(999)) && p()  && e('0');     // 测试步骤4：不存在的服务器ID测试
r($mrTest->getGiteaProjectsTester(2))   && p()  && e('array'); // 测试步骤5：验证返回数据格式为数组