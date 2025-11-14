#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

zenData('testreport')->gen(10);
zenData('case')->gen(10);
zenData('testrun')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getCaseIdList();
timeout=0
cid=19116

- 正常查询
 - 属性1 @1
 - 属性4 @4
- 查询创建者不为自己的数据
 - 属性5 @5
 - 属性8 @8
- 查询reportID为空的数据 @0

*/
$reportID = array(1, 2, 0);

$testreport = new testreportTest();

r($testreport->getCaseIdListTest($reportID[0])) && p('1,4') && e('1,4'); //正常查询
r($testreport->getCaseIdListTest($reportID[1])) && p('5,8') && e('5,8'); //查询创建者不为自己的数据
r($testreport->getCaseIdListTest($reportID[2])) && p()      && e('0');   //查询reportID为空的数据