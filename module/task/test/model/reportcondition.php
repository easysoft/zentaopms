#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=taskModel->reportCondition();
timeout=0
cid=18842

- 测试没有session条件 @1=1
- 测试负责的where查询条件 @id in (execution  = '4' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0')

- 测试小写的product查询条件 @id in (select * from zt_product)
- 测试大写的product查询条件 @id in (SELECT t1.id FROM zt_product)
- 测试大写的project查询条件 @id in (SELECT t1.id FROM zt_project)

*/

$taskTester = new taskModelTest();

r($taskTester->reportConditionTest())     && p() && e('1=1');                                                                                                     // 测试没有session条件

$sql = "execution  = '4' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0'";
r($taskTester->reportConditionTest($sql)) && p() && e("id in (execution  = '4' AND  status IN ('','wait','doing','done','pause','cancel') AND  deleted  = '0')"); // 测试负责的where查询条件

$sql = "select * from zt_product";
r($taskTester->reportConditionTest($sql)) && p() && e("id in (select * from zt_product)");     // 测试小写的product查询条件

$sql = "SELECT * FROM zt_product";
r($taskTester->reportConditionTest($sql)) && p() && e("id in (SELECT t1.id FROM zt_product)"); // 测试大写的product查询条件

$sql = "SELECT * FROM zt_project";
r($taskTester->reportConditionTest($sql)) && p() && e("id in (SELECT t1.id FROM zt_project)"); // 测试大写的project查询条件
