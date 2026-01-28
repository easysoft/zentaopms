#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoRelationList();
timeout=0
cid=15790

- 步骤1:验证返回结果是数组类型 @array
- 步骤2:验证包含父-子任务关联类型属性subTaskLink @父-子任务
- 步骤3:验证包含父-子需求关联类型属性subStoryLink @父-子需求
- 步骤4:验证包含重复对象关联类型属性duplicate @重复对象
- 步骤5:验证包含互相关联关系类型属性relates @互相关联

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$convertTest = new convertModelTest();

// 4. 🔴 强制要求:必须包含至少5个测试步骤
r(gettype($convertTest->getZentaoRelationListTest())) && p() && e('array'); // 步骤1:验证返回结果是数组类型
r($convertTest->getZentaoRelationListTest()) && p('subTaskLink') && e('父-子任务'); // 步骤2:验证包含父-子任务关联类型
r($convertTest->getZentaoRelationListTest()) && p('subStoryLink') && e('父-子需求'); // 步骤3:验证包含父-子需求关联类型
r($convertTest->getZentaoRelationListTest()) && p('duplicate') && e('重复对象'); // 步骤4:验证包含重复对象关联类型
r($convertTest->getZentaoRelationListTest()) && p('relates') && e('互相关联'); // 步骤5:验证包含互相关联关系类型