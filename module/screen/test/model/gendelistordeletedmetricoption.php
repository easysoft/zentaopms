#!/usr/bin/env php
<?php

/**

title=测试 screenModel::genDelistOrDeletedMetricOption();
timeout=0
cid=18228

- 步骤1：空component参数
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤2：空component对象
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤3：有option但无title
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤4：有option和title
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1
- 步骤5：完整component结构
 - 属性hasOption @1
 - 属性hasTitle @1
 - 属性hasNotFoundText @1
 - 属性isDeleted @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->genDelistOrDeletedMetricOptionTest(null)) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤1：空component参数
r($screenTest->genDelistOrDeletedMetricOptionTest((object)array())) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤2：空component对象
r($screenTest->genDelistOrDeletedMetricOptionTest((object)array('option' => new stdclass()))) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤3：有option但无title
r($screenTest->genDelistOrDeletedMetricOptionTest((object)array('option' => (object)array('title' => new stdclass())))) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤4：有option和title
r($screenTest->genDelistOrDeletedMetricOptionTest((object)array('id' => 1, 'name' => 'test', 'option' => (object)array('title' => (object)array('text' => 'existing'))))) && p('hasOption,hasTitle,hasNotFoundText,isDeleted') && e('1,1,1,1'); // 步骤5：完整component结构