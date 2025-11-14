#!/usr/bin/env php
<?php

/**

title=测试 searchModel::getOldQuery();
timeout=0
cid=18302

- 步骤1:正常查询存在的记录
 - 属性id @1
 - 属性account @admin
 - 属性module @task
- 步骤2:查询不存在的记录 @0
- 步骤3:查询第2条记录验证基本属性
 - 属性id @2
 - 属性module @task
 - 属性title @这是搜索条件名称2
- 步骤4:查询第3条记录验证基本属性
 - 属性id @3
 - 属性module @task
 - 属性title @这是搜索条件名称3
- 步骤5:查询ID为0的边界值 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';
su('admin');

zenData('userquery')->gen(10);

$search = new searchTest();

r($search->getOldQueryTest(1)) && p('id,account,module') && e('1,admin,task'); // 步骤1:正常查询存在的记录
r($search->getOldQueryTest(999)) && p() && e('0'); // 步骤2:查询不存在的记录
r($search->getOldQueryTest(2)) && p('id,module,title') && e('2,task,这是搜索条件名称2'); // 步骤3:查询第2条记录验证基本属性
r($search->getOldQueryTest(3)) && p('id,module,title') && e('3,task,这是搜索条件名称3'); // 步骤4:查询第3条记录验证基本属性
r($search->getOldQueryTest(0)) && p() && e('0'); // 步骤5:查询ID为0的边界值