#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testreport.class.php';

zdTable('testreport')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getById();
cid=1
pid=1

*/
$reportID = array(1, 2, 3, 0, 16);

$testreport = new testreportTest();

r($testreport->getByIdTest($reportID[0])) && p('id|owner|cases', '|') && e('1|user3|1,2,3,4');    // 正常查询 1
r($testreport->getByIdTest($reportID[1])) && p('id|owner|cases', '|') && e('2|user4|5,6,7,8');    // 正常查询 2
r($testreport->getByIdTest($reportID[2])) && p('id|owner|cases', '|') && e('3|user5|9,10,11,12'); // 正常查询 3
r($testreport->getByIdTest($reportID[3])) && p()                 && e('0');                       // reportID为空查询
r($testreport->getByIdTest($reportID[4])) && p()                 && e('0');                       // reportID不存在查询
