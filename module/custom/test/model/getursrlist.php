#!/usr/bin/env php
<?php

/**

title=测试 customModel::getURSRList();
cid=15905

- 测试步骤1：正常查询返回数据结构 >> 期望返回5条需求概念数据
- 测试步骤2：验证第一条数据完整属性 >> 期望包含key、ERName、SRName、URName、system
- 测试步骤3：验证第二条数据需求名称 >> 期望研发需求、用户需求字段正确
- 测试步骤4：验证第三条数据业务字段 >> 期望业务需求和key字段匹配
- 测试步骤5：验证第四条数据故事概念 >> 期望故事概念和系统标识正确

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);

su('admin');

$customTest = new customTest();

r($customTest->getURSRListTest()) && p() && e('5'); // 测试步骤1：正常查询返回数据结构
r($customTest->getURSRListTest()) && p('1:key,ERName,SRName,URName,system') && e('1,业务需求,软件需求,用户需求,1'); // 测试步骤2：验证第一条数据完整属性
r($customTest->getURSRListTest()) && p('2:SRName,URName') && e('研发需求,用户需求'); // 测试步骤3：验证第二条数据需求名称
r($customTest->getURSRListTest()) && p('3:ERName,key') && e('业务需求,3'); // 测试步骤4：验证第三条数据业务字段
r($customTest->getURSRListTest()) && p('4:SRName,system') && e('故事,1'); // 测试步骤5：验证第四条数据故事概念