#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchStoriesBySearch();
timeout=0
cid=17309

- 步骤1：contribute类型搜索，包含指定的需求 @0
- 步骤2：assigned类型搜索，当前用户指派的需求 @0
- 步骤3：contribute类型活跃状态 @0
- 步骤4：contribute类型ID条件查询 @0
- 步骤5：assigned类型ID条件查询 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('story')->gen(0);
zenData('product')->gen(0);
zenData('planstory')->gen(0);
zenData('productplan')->gen(0);
zenData('storyreview')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($myTest->fetchStoriesBySearchTest('t1.deleted = 0', 'contribute', 'id_desc', null, array(1 => 1, 2 => 2))) && p() && e('0'); // 步骤1：contribute类型搜索，包含指定的需求
r($myTest->fetchStoriesBySearchTest('t1.deleted = 0', 'assigned', 'id_desc', null, array())) && p() && e('0'); // 步骤2：assigned类型搜索，当前用户指派的需求
r($myTest->fetchStoriesBySearchTest('t1.status = "active"', 'contribute', 'id_asc', null, array())) && p() && e('0'); // 步骤3：contribute类型活跃状态
r($myTest->fetchStoriesBySearchTest('t1.deleted = 0 AND t1.id > 0', 'contribute', 'pri_desc', null, array())) && p() && e('0'); // 步骤4：contribute类型ID条件查询
r($myTest->fetchStoriesBySearchTest('t1.deleted = 0 AND t1.id > 0', 'assigned', 'title_asc', null, array())) && p() && e('0'); // 步骤5：assigned类型ID条件查询