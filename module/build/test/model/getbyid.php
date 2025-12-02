#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getByID();
timeout=0
cid=15491

- 测试步骤1：正常获取版本信息属性name @版本1
- 测试步骤2：获取第二个版本信息属性name @版本2
- 测试步骤3：测试不存在的版本ID @0
- 测试步骤4：测试负数版本ID @0
- 测试步骤5：测试setImgSize参数为true属性name @版本1
- 测试步骤6：测试版本信息完整性-产品名称属性productName @正常产品1
- 测试步骤7：测试边界值ID（0） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('product')->gen(10);
su('admin');

$buildTester = new buildTest();

r($buildTester->getByIDTest(1, false))    && p('name')         && e('版本1');       // 测试步骤1：正常获取版本信息
r($buildTester->getByIDTest(2, false))    && p('name')         && e('版本2');       // 测试步骤2：获取第二个版本信息
r($buildTester->getByIDTest(999, false))  && p()              && e('0');           // 测试步骤3：测试不存在的版本ID
r($buildTester->getByIDTest(-1, false))   && p()              && e('0');           // 测试步骤4：测试负数版本ID
r($buildTester->getByIDTest(1, true))     && p('name')         && e('版本1');       // 测试步骤5：测试setImgSize参数为true
r($buildTester->getByIDTest(1, false))    && p('productName')  && e('正常产品1');    // 测试步骤6：测试版本信息完整性-产品名称
r($buildTester->getByIDTest(0, false))    && p()              && e('0');           // 测试步骤7：测试边界值ID（0）