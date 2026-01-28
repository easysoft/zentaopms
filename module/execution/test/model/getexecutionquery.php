#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试 executionModel->getExecutionQuery();
timeout=0
cid=16314

- 没有queryID时，获取查询执行的SQL @(( 1 ) AND ( 1  AND t1.`status` = 'doing'))
- 有queryID时，获取查询执行的SQL @(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))
- queryID不存在时，获取查询执行的SQL @(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))
- queryID不存在时，获取查询执行的SQL @(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))
- queryID不存在时，获取查询执行的SQL @(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))

*/

zenData('user')->gen(5);
su('admin');

$userquery = zenData('userquery');
$userquery->id->range(1);
$userquery->account->range('admin');
$userquery->module->range('execution');
$userquery->title->range('搜索进行中的迭代');
$userquery->sql->range("`(( 1   AND `name`  LIKE '迭代' ) AND ( 1  AND `status` = 'doing'  ))`");
$userquery->form->range('``');
$userquery->gen(1);

$queryIdList = array(0, 1, 2, 3, 4);
$executionTester = new executionModelTest();
r($executionTester->getExecutionQueryTest($queryIdList[0])) && p() && e("(( 1 ) AND ( 1  AND t1.`status` = 'doing'))");                                // 没有queryID时，获取查询执行的SQL
r($executionTester->getExecutionQueryTest($queryIdList[1])) && p() && e("(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))"); // 有queryID时，获取查询执行的SQL
r($executionTester->getExecutionQueryTest($queryIdList[2])) && p() && e("(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))"); // queryID不存在时，获取查询执行的SQL
r($executionTester->getExecutionQueryTest($queryIdList[3])) && p() && e("(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))"); // queryID不存在时，获取查询执行的SQL
r($executionTester->getExecutionQueryTest($queryIdList[4])) && p() && e("(( 1   AND t1.`name`  LIKE '迭代' ) AND ( 1  AND t1.`status` = 'doing'  ))"); // queryID不存在时，获取查询执行的SQL
