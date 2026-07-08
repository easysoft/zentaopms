#!/usr/bin/env php
<?php

/**

title=测试 jobModel::import();
timeout=0
cid=16851

- 步骤1：导入有效的Gitlab仓库ID @1
- 步骤2：导入另一个有效的Gitlab仓库ID @1
- 步骤3：导入第三个仓库ID @1
- 步骤4：导入字符串类型的仓库ID @1
- 步骤5：导入第五个仓库ID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$jobTest = new jobModelTest();

// 5. 必须包含至少5个测试步骤
r($jobTest->importTest(1)) && p() && e('1');                               // 步骤1：导入有效的Gitlab仓库ID
r($jobTest->importTest(2)) && p() && e('1');                               // 步骤2：导入另一个有效的Gitlab仓库ID
r($jobTest->importTest(3)) && p() && e('1');                               // 步骤3：导入第三个仓库ID
r($jobTest->importTest('4')) && p() && e('1');                             // 步骤4：导入字符串类型的仓库ID
r($jobTest->importTest(5)) && p() && e('1');                               // 步骤5：导入第五个仓库ID