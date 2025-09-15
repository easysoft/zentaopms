#!/usr/bin/env php
<?php

/**

title=测试 projectTao::buildLinkForStory();
timeout=0
cid=0

- 步骤1：测试change方法 @/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=projectstory&f=story&projectID=%s
- 步骤2：测试create方法 @/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=projectstory&f=story&projectID=%s
- 步骤3：测试zerocase方法 @/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=project&f=testcase&projectID=%s
- 步骤4：测试未定义方法 @projectTao::buildLinkForStory(): Return value must be of type string, none returned

- 步骤5：测试空方法名 @projectTao::buildLinkForStory(): Return value must be of type string, none returned

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($projectTest->buildLinkForStoryTest('change')) && p() && e('/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=projectstory&f=story&projectID=%s'); // 步骤1：测试change方法
r($projectTest->buildLinkForStoryTest('create')) && p() && e('/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=projectstory&f=story&projectID=%s'); // 步骤2：测试create方法
r($projectTest->buildLinkForStoryTest('zerocase')) && p() && e('/home/z/rzto/module/project/test/tao/buildlinkforstory.php?m=project&f=testcase&projectID=%s'); // 步骤3：测试zerocase方法
r($projectTest->buildLinkForStoryTest('unknown')) && p() && e('projectTao::buildLinkForStory(): Return value must be of type string, none returned'); // 步骤4：测试未定义方法
r($projectTest->buildLinkForStoryTest('')) && p() && e('projectTao::buildLinkForStory(): Return value must be of type string, none returned'); // 步骤5：测试空方法名