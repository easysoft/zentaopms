#!/usr/bin/env php
<?php

/**

title=测试 customModel::getURSRList();
timeout=0
cid=15905

- 测试步骤2：验证第一条数据完整属性
 - 第1条的key属性 @1
 - 第1条的ERName属性 @业务需求
 - 第1条的SRName属性 @软件需求
 - 第1条的URName属性 @用户需求
 - 第1条的system属性 @1
- 测试步骤3：验证第二条数据需求名称
 - 第2条的SRName属性 @研发需求
 - 第2条的URName属性 @用户需求
- 测试步骤4：验证第三条数据业务字段
 - 第3条的ERName属性 @业务需求
 - 第3条的key属性 @3
- 测试步骤5：验证第四条数据故事概念
 - 第4条的SRName属性 @故事
 - 第4条的system属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);

su('admin');

$customTest = new customTest();

r($customTest->getURSRListTest()) && p('1:key,ERName,SRName,URName,system') && e('1,业务需求,软件需求,用户需求,1'); // 测试步骤1：验证第一条数据完整属性
r($customTest->getURSRListTest()) && p('2:SRName,URName') && e('研发需求,用户需求'); // 测试步骤2：验证第二条数据需求名称
r($customTest->getURSRListTest()) && p('3:ERName,key') && e('业务需求,3'); // 测试步骤3：验证第三条数据业务字段
r($customTest->getURSRListTest()) && p('4:SRName,system') && e('故事,1'); // 测试步骤4：验证第四条数据故事概念