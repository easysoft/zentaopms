#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupList();
timeout=0
cid=0

- 步骤1：正常实例获取备份列表属性code @200
- 步骤2：不存在的实例ID属性code @404
- 步骤3：无效实例ID(0)属性code @404
- 步骤4：负数实例ID属性code @404
- 步骤5：重复调用验证稳定性属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 创建测试实例，不依赖数据库
$cneTest = new cneTest();

// 测试用例1：模拟正常实例ID，在单元测试类中会模拟成功响应
r($cneTest->getBackupListTest(1)) && p('code') && e('200');         // 步骤1：正常实例获取备份列表
r($cneTest->getBackupListTest(999)) && p('code') && e('404');       // 步骤2：不存在的实例ID
r($cneTest->getBackupListTest(0)) && p('code') && e('404');         // 步骤3：无效实例ID(0)
r($cneTest->getBackupListTest(-1)) && p('code') && e('404');        // 步骤4：负数实例ID
r($cneTest->getBackupListTest(2)) && p('code') && e('200');         // 步骤5：重复调用验证稳定性