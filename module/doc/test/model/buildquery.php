#!/usr/bin/env php
<?php
/**

title=测试 docModel->buildQuery();
cid=1

- 测试type=mine 并且 queryID=0时，构造的搜索条件 @ 1 = 1
- 测试type=mine 并且 queryID=1时，构造的搜索条件 @(( 1 AND `title` LIKE '文档' ) AND ( 1 ))
- 测试type=mine 并且 queryID=2时，构造的搜索条件 @(( 1 AND 1) AND ( 1 ))
- 测试type=project 并且 queryID=0时，构造的搜索条件 @ 1 = 1
- 测试type=project 并且 queryID=1时，构造的搜索条件 @(( 1 AND `title` LIKE '文档' ) AND ( 1 ))
- 测试type=project 并且 queryID=2时，构造的搜索条件 @(( 1 AND 1) AND ( 1 ))
- 测试type=execution 并且 queryID=0时，构造的搜索条件 @ 1 = 1
- 测试type=execution 并且 queryID=1时，构造的搜索条件 @(( 1 AND `title` LIKE '文档' ) AND ( 1 ))
- 测试type=execution 并且 queryID=2时，构造的搜索条件 @(( 1 AND 1) AND ( 1 ))
- 测试type=product 并且 queryID=0时，构造的搜索条件 @ 1 = 1
- 测试type=product 并且 queryID=1时，构造的搜索条件 @(( 1 AND `title` LIKE '文档' ) AND ( 1 ))
- 测试type=product 并且 queryID=2时，构造的搜索条件 @(( 1 AND 1) AND ( 1 ))
- 测试type=custom 并且 queryID=0时，构造的搜索条件 @ 1 = 1
- 测试type=custom 并且 queryID=1时，构造的搜索条件 @(( 1 AND `title` LIKE '文档' ) AND ( 1 ))
- 测试type=custom 并且 queryID=2时，构造的搜索条件 @(( 1 AND 1) AND ( 1 ))

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1-2');
$userqueryTable->sql->range("`(( 1 AND `title` LIKE '文档' ) AND ( 1 ))`,`(( 1 AND `lib` = 'all') AND ( 1 ))`");
$userqueryTable->gen(2);

zdTable('user')->gen(5);
su('admin');

$queries  = array(0, 1, 2);
$typeList = array('mine', 'project', 'execution', 'product', 'custom');

$docTester = new docTest();
r($docTester->buildQueryTest($typeList[0], $queries[0])) && p() && e(" 1 = 1");                                    // 测试type=mine 并且 queryID=0时，构造的搜索条件
r($docTester->buildQueryTest($typeList[0], $queries[1])) && p() && e("(( 1 AND `title` LIKE '文档' ) AND ( 1 ))"); // 测试type=mine 并且 queryID=1时，构造的搜索条件
r($docTester->buildQueryTest($typeList[0], $queries[2])) && p() && e("(( 1 AND 1) AND ( 1 ))");                    // 测试type=mine 并且 queryID=2时，构造的搜索条件
r($docTester->buildQueryTest($typeList[1], $queries[0])) && p() && e(" 1 = 1");                                    // 测试type=project 并且 queryID=0时，构造的搜索条件
r($docTester->buildQueryTest($typeList[1], $queries[1])) && p() && e("(( 1 AND `title` LIKE '文档' ) AND ( 1 ))"); // 测试type=project 并且 queryID=1时，构造的搜索条件
r($docTester->buildQueryTest($typeList[1], $queries[2])) && p() && e("(( 1 AND 1) AND ( 1 ))");                    // 测试type=project 并且 queryID=2时，构造的搜索条件
r($docTester->buildQueryTest($typeList[2], $queries[0])) && p() && e(" 1 = 1");                                    // 测试type=execution 并且 queryID=0时，构造的搜索条件
r($docTester->buildQueryTest($typeList[2], $queries[1])) && p() && e("(( 1 AND `title` LIKE '文档' ) AND ( 1 ))"); // 测试type=execution 并且 queryID=1时，构造的搜索条件
r($docTester->buildQueryTest($typeList[2], $queries[2])) && p() && e("(( 1 AND 1) AND ( 1 ))");                    // 测试type=execution 并且 queryID=2时，构造的搜索条件
r($docTester->buildQueryTest($typeList[3], $queries[0])) && p() && e(" 1 = 1");                                    // 测试type=product 并且 queryID=0时，构造的搜索条件
r($docTester->buildQueryTest($typeList[3], $queries[1])) && p() && e("(( 1 AND `title` LIKE '文档' ) AND ( 1 ))"); // 测试type=product 并且 queryID=1时，构造的搜索条件
r($docTester->buildQueryTest($typeList[3], $queries[2])) && p() && e("(( 1 AND 1) AND ( 1 ))");                    // 测试type=product 并且 queryID=2时，构造的搜索条件
r($docTester->buildQueryTest($typeList[4], $queries[0])) && p() && e(" 1 = 1");                                    // 测试type=custom 并且 queryID=0时，构造的搜索条件
r($docTester->buildQueryTest($typeList[4], $queries[1])) && p() && e("(( 1 AND `title` LIKE '文档' ) AND ( 1 ))"); // 测试type=custom 并且 queryID=1时，构造的搜索条件
r($docTester->buildQueryTest($typeList[4], $queries[2])) && p() && e("(( 1 AND 1) AND ( 1 ))");                    // 测试type=custom 并且 queryID=2时，构造的搜索条件
