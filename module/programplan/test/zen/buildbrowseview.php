#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildBrowseView();
timeout=0
cid=17786

- 步骤1：正常参数gantt类型属性success @1
- 步骤2：gantt类型带排序和浏览类型属性type @gantt
- 步骤3：lists类型测试属性type @lists
- 步骤4：assignedTo类型带搜索属性type @assignedTo
- 步骤5：搜索功能测试属性projectID @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 不使用zendata，直接在测试方法中模拟数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programplanTest = new programplanZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($programplanTest->buildBrowseViewTest(1, 1, 'gantt', 'order_asc', '')) && p('success') && e('1'); // 步骤1：正常参数gantt类型
r($programplanTest->buildBrowseViewTest(2, 1, 'gantt', 'id_desc', 'all')) && p('type') && e('gantt'); // 步骤2：gantt类型带排序和浏览类型
r($programplanTest->buildBrowseViewTest(3, 2, 'lists', 'name_asc', '')) && p('type') && e('lists'); // 步骤3：lists类型测试
r($programplanTest->buildBrowseViewTest(1, 1, 'assignedTo', 'order_asc', 'bysearch')) && p('type') && e('assignedTo'); // 步骤4：assignedTo类型带搜索
r($programplanTest->buildBrowseViewTest(5, 2, 'gantt', 'begin_asc', 'bysearch')) && p('projectID') && e('5'); // 步骤5：搜索功能测试