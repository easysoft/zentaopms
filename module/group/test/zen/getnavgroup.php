#!/usr/bin/env php
<?php

/**

title=测试 groupZen::getNavGroup();
timeout=0
cid=0

- 步骤1：正常获取NavGroup数据，验证返回正确数量的分组 @13
- 步骤2：验证admin分组包含group模块第admin条的group属性 @group
- 步骤3：验证product分组包含story模块第product条的story属性 @story
- 步骤4：验证qa分组中testcase映射为case第qa条的case属性 @case
- 步骤5：验证execution分组包含task模块第execution条的task属性 @task

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/groupzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$groupTest = new groupZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r(count($groupTest->getNavGroupTest())) && p() && e(13); // 步骤1：正常获取NavGroup数据，验证返回正确数量的分组
r($groupTest->getNavGroupTest()) && p('admin:group') && e('group'); // 步骤2：验证admin分组包含group模块
r($groupTest->getNavGroupTest()) && p('product:story') && e('story'); // 步骤3：验证product分组包含story模块
r($groupTest->getNavGroupTest()) && p('qa:case') && e('case'); // 步骤4：验证qa分组中testcase映射为case
r($groupTest->getNavGroupTest()) && p('execution:task') && e('task'); // 步骤5：验证execution分组包含task模块