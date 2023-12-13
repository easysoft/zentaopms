#!/usr/bin/env php
<?php
/**

title=测试 designModel->getBySearch();
cid=1

- 测试projectID=0, productID=0, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=0时，按照标题倒序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=1时，按照id倒序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=1时，按照id正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=1时，按照标题正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID=1时，按照标题倒序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID不存在时，按照id倒序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID不存在时，按照id正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID不存在时，按照标题正序排列的设计列表数据 @0
- 测试projectID=0, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据 @0
- 测试projectID=0, productID=1, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID=0, productID=1, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID=0, productID=1, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID=0, productID=1, queryID=0时，按照标题倒序排列的设计列表数据 @0
- 测试projectID=11, productID=0, queryID=0时，按照id倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=0时，按照id正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=0时，按照标题正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=0时，按照标题倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=1时，按照id倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=1时，按照id正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=1时，按照标题正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID=1时，按照标题倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID不存在时，按照id倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID不存在时，按照id正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID不存在时，按照标题正序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
- 测试projectID=11, productID=1, queryID=0时，按照id倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=0时，按照id正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=0时，按照标题正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=0时，按照标题倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=1时，按照id倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=1时，按照id正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=1时，按照标题正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID=1时，按照标题倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID不存在时，按照id倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID不存在时，按照id正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID不存在时，按照标题正序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID=1, queryID不存在时，按照标题倒序排列的设计列表数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
- 测试projectID=11, productID不存在, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID=11, productID不存在, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID=11, productID不存在, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID=11, productID不存在, queryID=0时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=0时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=1时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=1时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=1时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID=1时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID不存在时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID不存在时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID不存在时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=0时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=1时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=1时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=1时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID=1时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID不存在时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID不存在时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID不存在时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=1, queryID不存在时，按照标题倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=2, queryID=0时，按照id倒序排列的设计列表数据 @0
- 测试projectID不存在, productID=2, queryID=0时，按照id正序排列的设计列表数据 @0
- 测试projectID不存在, productID=2, queryID=0时，按照标题正序排列的设计列表数据 @0
- 测试projectID不存在, productID=2, queryID=0时，按照标题倒序排列的设计列表数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

$userqueryTable = zdTable('userquery');
$userqueryTable->id->range('1');
$userqueryTable->sql->range("`(( 1  AND `name`  LIKE '%设计%' ) AND ( 1  ))`");
$userqueryTable->module->range('design');
$userqueryTable->gen(1);

zdTable('design')->config('design')->gen(30);

$projects = array(0, 11, 1);
$products = array(0, 1, 11);
$queries  = array(0, 1, 2);
$sorts    = array('id_desc', 'id_asc', 'name_asc', 'name_desc');

$designTester = new designTest();
r($designTester->getBySearchTest($projects[0], $products[0], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[1], $sorts[0])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=1时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[1], $sorts[1])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=1时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[1], $sorts[2])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=1时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[1], $sorts[3])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID=1时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[2], $sorts[0])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID不存在时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[2], $sorts[1])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID不存在时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[2], $sorts[2])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID不存在时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[0], $queries[2], $sorts[3])) && p()                         && e('0');          // 测试projectID=0, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[1], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID=0, productID=1, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[1], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID=0, productID=1, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[1], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID=0, productID=1, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[0], $products[1], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID=0, productID=1, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[0], $sorts[0])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[0], $sorts[1])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[0], $sorts[2])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[0], $sorts[3])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[1], $sorts[0])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=1时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[1], $sorts[1])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=1时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[1], $sorts[2])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=1时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[1], $sorts[3])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID=1时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[2], $sorts[0])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID不存在时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[2], $sorts[1])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID不存在时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[2], $sorts[2])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID不存在时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[0], $queries[2], $sorts[3])) && p('1:name,project,product') && e('设计1,11,0'); // 测试projectID=11, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[0], $sorts[0])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[0], $sorts[1])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[0], $sorts[2])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[0], $sorts[3])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[1], $sorts[0])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=1时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[1], $sorts[1])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=1时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[1], $sorts[2])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=1时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[1], $sorts[3])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID=1时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[2], $sorts[0])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID不存在时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[2], $sorts[1])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID不存在时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[2], $sorts[2])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID不存在时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[1], $queries[2], $sorts[3])) && p('3:name,project,product') && e('设计3,11,1'); // 测试projectID=11, productID=1, queryID不存在时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[2], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID=11, productID不存在, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[2], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID=11, productID不存在, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[2], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID=11, productID不存在, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[1], $products[2], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID=11, productID不存在, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[1], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=1时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[1], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=1时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[1], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=1时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[1], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID=1时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[2], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID不存在时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[2], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID不存在时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[2], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID不存在时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[0], $queries[2], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=0, queryID不存在时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=0时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[1], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=1时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[1], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=1时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[1], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=1时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[1], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID=1时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[2], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID不存在时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[2], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID不存在时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[2], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID不存在时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[1], $queries[2], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=1, queryID不存在时，按照标题倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[2], $queries[0], $sorts[0])) && p()                         && e('0');          // 测试projectID不存在, productID=2, queryID=0时，按照id倒序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[2], $queries[0], $sorts[1])) && p()                         && e('0');          // 测试projectID不存在, productID=2, queryID=0时，按照id正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[2], $queries[0], $sorts[2])) && p()                         && e('0');          // 测试projectID不存在, productID=2, queryID=0时，按照标题正序排列的设计列表数据
r($designTester->getBySearchTest($projects[2], $products[2], $queries[0], $sorts[3])) && p()                         && e('0');          // 测试projectID不存在, productID=2, queryID=0时，按照标题倒序排列的设计列表数据
