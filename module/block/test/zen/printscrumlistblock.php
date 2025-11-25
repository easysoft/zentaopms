#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumListBlock();
timeout=0
cid=15288

- 步骤1:测试正常情况下传入合法type参数和count参数
 - 属性type @undone
 - 属性count @15
- 步骤2:测试type参数包含特殊字符时验证失败属性hasValidation @0
- 步骤3:测试count为0时的处理属性count @0
- 步骤4:测试projectID为0时的处理
 - 属性type @all
 - 属性projectID @0
- 步骤5:测试不同projectID和count参数
 - 属性type @doing
 - 属性count @20
- 步骤6:测试不同的type类型参数
 - 属性type @done
 - 属性count @10

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$blockTest = new blockTest();

// 4. 强制要求:必须包含至少5个测试步骤
r($blockTest->printScrumListBlockTest('undone', 1, 15)) && p('type,count') && e('undone,15'); // 步骤1:测试正常情况下传入合法type参数和count参数
r($blockTest->printScrumListBlockTest('invalid-type', 1, 5)) && p('hasValidation') && e('0'); // 步骤2:测试type参数包含特殊字符时验证失败
r($blockTest->printScrumListBlockTest('undone', 1, 0)) && p('count') && e('0'); // 步骤3:测试count为0时的处理
r($blockTest->printScrumListBlockTest('all', 0, 5)) && p('type,projectID') && e('all,0'); // 步骤4:测试projectID为0时的处理
r($blockTest->printScrumListBlockTest('doing', 2, 20)) && p('type,count') && e('doing,20'); // 步骤5:测试不同projectID和count参数
r($blockTest->printScrumListBlockTest('done', 1, 10)) && p('type,count') && e('done,10'); // 步骤6:测试不同的type类型参数