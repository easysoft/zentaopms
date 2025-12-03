#!/usr/bin/env php
<?php

/**

title=测试 searchModel::getOldQuery();
timeout=0
cid=18302

- 测试查询ID为1的旧版搜索条件
 - 属性module @task
 - 属性title @这是搜索条件名称1
- 测试查询ID为2的旧版搜索条件
 - 属性module @task
 - 属性title @这是搜索条件名称2
- 测试查询ID为999不存在的搜索条件 @0
- 测试查询ID为0无效的搜索条件 @0
- 测试不同模块的查询条件
 - 属性module @task
 - 属性title @这是搜索条件名称3
- 测试查询ID为4的旧版搜索条件
 - 属性module @task
 - 属性title @这是搜索条件名称4
- 测试查询ID为5的旧版搜索条件
 - 属性module @task
 - 属性title @这是搜索条件名称5

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zenData('userquery')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$searchTest = new searchModelTest();

// 5. 测试步骤(至少5个)
r($searchTest->getOldQueryTest(1)) && p('module,title') && e('task,这是搜索条件名称1'); // 测试查询ID为1的旧版搜索条件
r($searchTest->getOldQueryTest(2)) && p('module,title') && e('task,这是搜索条件名称2'); // 测试查询ID为2的旧版搜索条件
r($searchTest->getOldQueryTest(999)) && p() && e('0'); // 测试查询ID为999不存在的搜索条件
r($searchTest->getOldQueryTest(0)) && p() && e('0'); // 测试查询ID为0无效的搜索条件
r($searchTest->getOldQueryTest(3)) && p('module,title') && e('task,这是搜索条件名称3'); // 测试不同模块的查询条件
r($searchTest->getOldQueryTest(4)) && p('module,title') && e('task,这是搜索条件名称4'); // 测试查询ID为4的旧版搜索条件
r($searchTest->getOldQueryTest(5)) && p('module,title') && e('task,这是搜索条件名称5'); // 测试查询ID为5的旧版搜索条件