#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoObjectList();
timeout=0
cid=15789

- 步骤1:enableER=true且URAndSR=true时,返回所有对象(含epic和requirement)属性epic @业务需求
- 步骤2:enableER=false时,不包含epic对象属性requirement @用户需求
- 步骤3:URAndSR=false时,不包含requirement对象属性epic @业务需求
- 步骤4:enableER=false且URAndSR=false时,不包含epic和requirement @5
- 步骤5:验证返回的是数组类型 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->getZentaoObjectListTest()) && p('epic') && e('业务需求'); // 步骤1:enableER=true且URAndSR=true时,返回所有对象(含epic和requirement)
r($convertTest->getZentaoObjectListTestWithoutER()) && p('requirement') && e('用户需求'); // 步骤2:enableER=false时,不包含epic对象
r($convertTest->getZentaoObjectListTestWithoutUR()) && p('epic') && e('业务需求'); // 步骤3:URAndSR=false时,不包含requirement对象
r(count($convertTest->getZentaoObjectListTestWithoutERAndUR())) && p() && e('5'); // 步骤4:enableER=false且URAndSR=false时,不包含epic和requirement
r(gettype($convertTest->getZentaoObjectListTest())) && p() && e('array'); // 步骤5:验证返回的是数组类型