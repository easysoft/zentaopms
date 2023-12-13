#!/usr/bin/env php
<?php
/**

title=测试 designModel->getList();
cid=1

- 测试空数据 @0
- 获取projectID=1,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
 - 第1条的type属性 @HLDS
- 获取projectID=1,prouctID=0,type='all',query=0时，所有按照id正序排列设计数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
 - 第1条的type属性 @HLDS
- 获取projectID=1,prouctID=1,type='all',query=0时，所有按照id倒序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='all',query=0时，所有按照id正序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID不存在,type='all',query=1时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='all',query=1时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=1,type='bySearch',query=0时，所有按照id倒序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='bySearch',query=0时，所有按照id正序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='bySearch',query=1时，所有按照id倒序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='bySearch',query=1时，所有按照id正序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='bySearch',query不存在时，所有按照id倒序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='bySearch',query不存在时，所有按照id正序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID不存在,type='bySearch',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='bySearch',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=0,type='HLDS',query=0时，所有按照id倒序排列设计数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
 - 第1条的type属性 @HLDS
- 获取projectID=1,prouctID=0,type='HLDS',query=0时，所有按照id正序排列设计数据
 - 第1条的name属性 @设计1
 - 第1条的project属性 @11
 - 第1条的product属性 @0
 - 第1条的type属性 @HLDS
- 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id倒序排列设计数据
 - 第5条的name属性 @设计5
 - 第5条的project属性 @11
 - 第5条的product属性 @1
 - 第5条的type属性 @HLDS
- 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id正序排列设计数据
 - 第5条的name属性 @设计5
 - 第5条的project属性 @11
 - 第5条的product属性 @1
 - 第5条的type属性 @HLDS
- 获取projectID=1,prouctID不存在,type='HLDS',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='HLDS',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=0,type='DDS',query=0时，所有按照id倒序排列设计数据
 - 第10条的name属性 @设计10
 - 第10条的project属性 @11
 - 第10条的product属性 @7
 - 第10条的type属性 @DDS
- 获取projectID=1,prouctID=0,type='DDS',query=0时，所有按照id正序排列设计数据
 - 第10条的name属性 @设计10
 - 第10条的project属性 @11
 - 第10条的product属性 @7
 - 第10条的type属性 @DDS
- 获取projectID=1,prouctID=1,type='DDS',query=0时，所有按照id倒序排列设计数据
 - 第6条的name属性 @设计6
 - 第6条的project属性 @11
 - 第6条的product属性 @1
 - 第6条的type属性 @DDS
- 获取projectID=1,prouctID=1,type='DDS',query=0时，所有按照id正序排列设计数据
 - 第6条的name属性 @设计6
 - 第6条的project属性 @11
 - 第6条的product属性 @1
 - 第6条的type属性 @DDS
- 获取projectID=1,prouctID不存在,type='DDS',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='DDS',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=0,type='DBDS',query=0时，所有按照id倒序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=0,type='DBDS',query=0时，所有按照id正序排列设计数据
 - 第3条的name属性 @设计3
 - 第3条的project属性 @11
 - 第3条的product属性 @1
 - 第3条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id倒序排列设计数据
 - 第7条的name属性 @设计7
 - 第7条的project属性 @11
 - 第7条的product属性 @1
 - 第7条的type属性 @DBDS
- 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id正序排列设计数据
 - 第7条的name属性 @设计7
 - 第7条的project属性 @11
 - 第7条的product属性 @1
 - 第7条的type属性 @DBDS
- 获取projectID=1,prouctID不存在,type='DBDS',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='DBDS',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=0,type='ADS',query=0时，所有按照id倒序排列设计数据
 - 第4条的name属性 @设计4
 - 第4条的project属性 @11
 - 第4条的product属性 @1
 - 第4条的type属性 @ADS
- 获取projectID=1,prouctID=0,type='ADS',query=0时，所有按照id正序排列设计数据
 - 第4条的name属性 @设计4
 - 第4条的project属性 @11
 - 第4条的product属性 @1
 - 第4条的type属性 @ADS
- 获取projectID=1,prouctID=1,type='ADS',query=0时，所有按照id倒序排列设计数据
 - 第8条的name属性 @设计8
 - 第8条的project属性 @11
 - 第8条的product属性 @1
 - 第8条的type属性 @ADS
- 获取projectID=1,prouctID=1,type='ADS',query=0时，所有按照id正序排列设计数据
 - 第8条的name属性 @设计8
 - 第8条的project属性 @11
 - 第8条的product属性 @1
 - 第8条的type属性 @ADS
- 获取projectID=1,prouctID不存在,type='ADS',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type='ADS',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=0,type=不存在,query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID=0,type=不存在,query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID=1,type=不存在,query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID=1,type=不存在,query=0时，所有按照id正序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type=不存在,query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID=1,prouctID不存在,type=不存在,query=0时，所有按照id正序排列设计数据 @0
- 获取projectID不存在,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID不存在,prouctID=0,type='',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID不存在,prouctID=1,type='',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID不存在,prouctID=1,type='',query=0时，所有按照id正序排列设计数据 @0
- 获取projectID不存在,prouctID不存在,type='',query=0时，所有按照id倒序排列设计数据 @0
- 获取projectID不存在,prouctID不存在,type='',query=0时，所有按照id正序排列设计数据 @0

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
$types    = array('', 'all', 'bySearch', 'HLDS', 'DDS', 'DBDS', 'ADS', 'test');
$queries  = array(0, 1, 2);
$sorts    = array('id_desc', 'id_asc');

$designTester = new designTest();
r($designTester->getListTest($projects[0], $products[0], $types[0], $queries[0], $sorts[0])) && p()                               && e('0');               // 测试空数据
r($designTester->getListTest($projects[1], $products[0], $types[0], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[1], $queries[0], $sorts[0])) && p('1:name,project,product,type')  && e('设计1,11,0,HLDS'); // 获取projectID=1,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[1], $queries[0], $sorts[1])) && p('1:name,project,product,type')  && e('设计1,11,0,HLDS'); // 获取projectID=1,prouctID=0,type='all',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[1], $queries[0], $sorts[0])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='all',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[1], $queries[0], $sorts[1])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='all',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[1], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='all',query=1时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[1], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='all',query=1时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[0], $sorts[0])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[0], $sorts[1])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[1], $sorts[0])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query=1时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[1], $sorts[1])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query=1时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[2], $sorts[0])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query不存在时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[2], $queries[2], $sorts[1])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='bySearch',query不存在时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[2], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='bySearch',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[2], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='bySearch',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[3], $queries[0], $sorts[0])) && p('1:name,project,product,type')  && e('设计1,11,0,HLDS'); // 获取projectID=1,prouctID=0,type='HLDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[3], $queries[0], $sorts[1])) && p('1:name,project,product,type')  && e('设计1,11,0,HLDS'); // 获取projectID=1,prouctID=0,type='HLDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[3], $queries[0], $sorts[0])) && p('5:name,project,product,type')  && e('设计5,11,1,HLDS'); // 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[3], $queries[0], $sorts[1])) && p('5:name,project,product,type')  && e('设计5,11,1,HLDS'); // 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[3], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='HLDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[3], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='HLDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[4], $queries[0], $sorts[0])) && p('10:name,project,product,type') && e('设计10,11,7,DDS'); // 获取projectID=1,prouctID=0,type='DDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[4], $queries[0], $sorts[1])) && p('10:name,project,product,type') && e('设计10,11,7,DDS'); // 获取projectID=1,prouctID=0,type='DDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[4], $queries[0], $sorts[0])) && p('6:name,project,product,type')  && e('设计6,11,1,DDS');  // 获取projectID=1,prouctID=1,type='DDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[4], $queries[0], $sorts[1])) && p('6:name,project,product,type')  && e('设计6,11,1,DDS');  // 获取projectID=1,prouctID=1,type='DDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[4], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='DDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[4], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='DDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[5], $queries[0], $sorts[0])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=0,type='DBDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[5], $queries[0], $sorts[1])) && p('3:name,project,product,type')  && e('设计3,11,1,DBDS'); // 获取projectID=1,prouctID=0,type='DBDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[5], $queries[0], $sorts[0])) && p('7:name,project,product,type')  && e('设计7,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[5], $queries[0], $sorts[1])) && p('7:name,project,product,type')  && e('设计7,11,1,DBDS'); // 获取projectID=1,prouctID=1,type='DBDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[5], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='DBDS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[5], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='DBDS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[6], $queries[0], $sorts[0])) && p('4:name,project,product,type')  && e('设计4,11,1,ADS');  // 获取projectID=1,prouctID=0,type='ADS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[6], $queries[0], $sorts[1])) && p('4:name,project,product,type')  && e('设计4,11,1,ADS');  // 获取projectID=1,prouctID=0,type='ADS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[6], $queries[0], $sorts[0])) && p('8:name,project,product,type')  && e('设计8,11,1,ADS');  // 获取projectID=1,prouctID=1,type='ADS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[6], $queries[0], $sorts[1])) && p('8:name,project,product,type')  && e('设计8,11,1,ADS');  // 获取projectID=1,prouctID=1,type='ADS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[6], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='ADS',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[6], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type='ADS',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[7], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID=0,type=不存在,query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[0], $types[7], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID=0,type=不存在,query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[7], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID=1,type=不存在,query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[1], $types[7], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID=1,type=不存在,query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[7], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type=不存在,query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[1], $products[2], $types[7], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID=1,prouctID不存在,type=不存在,query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[0], $products[0], $types[0], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID不存在,prouctID=0,type='',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[0], $products[0], $types[0], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID不存在,prouctID=0,type='',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[0], $products[1], $types[0], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID不存在,prouctID=1,type='',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[0], $products[1], $types[0], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID不存在,prouctID=1,type='',query=0时，所有按照id正序排列设计数据
r($designTester->getListTest($projects[0], $products[2], $types[0], $queries[0], $sorts[0])) && p()                               && e('0');               // 获取projectID不存在,prouctID不存在,type='',query=0时，所有按照id倒序排列设计数据
r($designTester->getListTest($projects[0], $products[2], $types[0], $queries[0], $sorts[1])) && p()                               && e('0');               // 获取projectID不存在,prouctID不存在,type='',query=0时，所有按照id正序排列设计数据
