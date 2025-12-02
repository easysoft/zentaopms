#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

zenData('testreport')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getById();
timeout=0
cid=19115

- 正常查询 1
 - 属性id @1
 - 属性owner @user3
 - 属性cases @1,2,3,4
- 正常查询 2
 - 属性id @2
 - 属性owner @user4
 - 属性cases @5,6,7,8
- 正常查询 3
 - 属性id @3
 - 属性owner @user5
 - 属性cases @9,10,11,12
- reportID为空查询 @0
- reportID不存在查询 @0

*/
$reportID = array(1, 2, 3, 0, 16);

$testreport = new testreportTest();

r($testreport->getByIdTest($reportID[0])) && p('id|owner|cases', '|') && e('1|user3|1,2,3,4');    // 正常查询 1
r($testreport->getByIdTest($reportID[1])) && p('id|owner|cases', '|') && e('2|user4|5,6,7,8');    // 正常查询 2
r($testreport->getByIdTest($reportID[2])) && p('id|owner|cases', '|') && e('3|user5|9,10,11,12'); // 正常查询 3
r($testreport->getByIdTest($reportID[3])) && p()                 && e('0');                       // reportID为空查询
r($testreport->getByIdTest($reportID[4])) && p()                 && e('0');                       // reportID不存在查询