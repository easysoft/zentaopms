#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiDeleteTagPriv();
timeout=0
cid=16594

- 步骤1：空gitlabID @~~
- 步骤2：无效projectID @~~
- 步骤3：不存在的标签 @~~
- 步骤4：特殊字符标签 @~~
- 步骤5：有效参数但标签不存在 @~~

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$gitlabTest = new gitlabModelTest();

// 4. 执行测试步骤（必须至少5个）
r($gitlabTest->apiDeleteTagPrivTest(0, 1, 'test_tag') === null) && p() && e('1'); // 步骤1：空gitlabID
r($gitlabTest->apiDeleteTagPrivTest(1, 999, 'test_tag') === null) && p() && e('1'); // 步骤2：无效projectID
r($gitlabTest->apiDeleteTagPrivTest(1, 2, 'nonexistent_tag') === null) && p() && e('1'); // 步骤3：不存在的标签
r($gitlabTest->apiDeleteTagPrivTest(1, 2, 'tag/with/special-chars') === null) && p() && e('1'); // 步骤4：特殊字符标签
r($gitlabTest->apiDeleteTagPrivTest(1, 2, 'valid_tag') === null) && p() && e('1'); // 步骤5：有效参数但标签不存在
