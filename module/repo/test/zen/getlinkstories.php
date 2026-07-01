#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkStories();
timeout=0
cid=18143

- 步骤1：正常情况获取需求数量 @2
- 步骤2：搜索方式获取需求数量 @1
- 步骤3：无效产品ID @0
- 步骤4：空产品数组 @0
- 步骤5：分页验证 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 简化测试，不使用zendata数据准备

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($repoTest->getLinkStoriesTest(1, '1', 'all', array(1 => (object)array('id' => 1, 'name' => '产品1')), 'id_desc', (object)array('recPerPage' => 20, 'pageID' => 1), 0))) && p() && e('2'); // 步骤1：正常情况获取需求数量
r(count($repoTest->getLinkStoriesTest(1, '2', 'bysearch', array(1 => (object)array('id' => 1, 'name' => '产品1')), 'id_desc', (object)array('recPerPage' => 20, 'pageID' => 1), 1))) && p() && e('1'); // 步骤2：搜索方式获取需求数量
r(count($repoTest->getLinkStoriesTest(1, '3', 'all', array(999 => (object)array('id' => 999, 'name' => '不存在产品')), 'id_desc', (object)array('recPerPage' => 20, 'pageID' => 1), 0))) && p() && e('0'); // 步骤3：无效产品ID
r(count($repoTest->getLinkStoriesTest(1, '1', 'all', array(), 'id_desc', (object)array('recPerPage' => 20, 'pageID' => 1), 0))) && p() && e('0'); // 步骤4：空产品数组
r(count($repoTest->getLinkStoriesTest(1, '1', 'all', array(1 => (object)array('id' => 1, 'name' => '产品1'), 2 => (object)array('id' => 2, 'name' => '产品2')), 'id_desc', (object)array('recPerPage' => 5, 'pageID' => 1), 0))) && p() && e('3'); // 步骤5：分页验证