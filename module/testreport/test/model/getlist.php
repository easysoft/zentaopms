#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreport.unittest.class.php';

zenData('testreport')->gen(30);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testreportModel->getList();
timeout=0
cid=19118

- 查询objectID 1 objectType product extra 0 orderBy id_desc @21,11,1

- 查询objectID 1 objectType project extra 0 orderBy id_desc @0
- 查询objectID 1 objectType execution extra 0 orderBy id_desc @0
- 查询objectID 1 objectType product extra 1 orderBy id_desc @1
- 查询objectID 1 objectType product extra 11 orderBy id_desc @0
- 查询objectID 1 objectType product extra 101 orderBy id_desc @0
- 查询objectID 1 objectType product extra 0 orderBy id_asc @1,11,21

- 查询objectID 1 objectType product extra 101 orderBy id_asc @1
- 查询objectID 11 objectType product extra 0 orderBy id_desc @0
- 查询objectID 11 objectType project extra 0 orderBy id_desc @21,11,1

- 查询objectID 11 objectType execution extra 0 orderBy id_desc @0
- 查询objectID 11 objectType project extra 1 orderBy id_desc @21,11,1

- 查询objectID 11 objectType project extra 11 orderBy id_desc @21,11,1

- 查询objectID 11 objectType project extra 101 orderBy id_desc @21,11,1

- 查询objectID 11 objectType project extra 0 orderBy id_asc @1,11,21

- 查询objectID 101 objectType product extra 0 orderBy id_desc @0
- 查询objectID 101 objectType project extra 0 orderBy id_desc @0
- 查询objectID 101 objectType execution extra 0 orderBy id_desc @21,11,1

- 查询objectID 101 objectType execution extra 1 orderBy id_desc @21,11,1

- 查询objectID 101 objectType execution extra 11 orderBy id_desc @21,11,1

- 查询objectID 101 objectType execution extra 101 orderBy id_desc @21,11,1

- 查询objectID 101 objectType execution extra 0 orderBy id_asc @1,11,21

*/
$objectID   = array(1, 11, 101);
$objectType = array('product', 'project', 'execution');
$extra      = array(0, 1, 11, 101);
$orderBy    = array('id_desc', 'id_asc');

$testreport = new testreportTest();

r($testreport->getListTest($objectID[0], $objectType[0], $extra[0], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 1 objectType product extra 0 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[1], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 1 objectType project extra 0 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[2], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 1 objectType execution extra 0 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[0], $extra[1], $orderBy[0])) && p() && e('1');       // 查询objectID 1 objectType product extra 1 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[0], $extra[2], $orderBy[0])) && p() && e('0');       // 查询objectID 1 objectType product extra 11 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[0], $extra[3], $orderBy[0])) && p() && e('0');       // 查询objectID 1 objectType product extra 101 orderBy id_desc
r($testreport->getListTest($objectID[0], $objectType[0], $extra[0], $orderBy[1])) && p() && e('1,11,21'); // 查询objectID 1 objectType product extra 0 orderBy id_asc
r($testreport->getListTest($objectID[0], $objectType[0], $extra[1], $orderBy[1])) && p() && e('1');       // 查询objectID 1 objectType product extra 101 orderBy id_asc

r($testreport->getListTest($objectID[1], $objectType[0], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 11 objectType product extra 0 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[1], $extra[0], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 11 objectType project extra 0 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[2], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 11 objectType execution extra 0 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[1], $extra[1], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 11 objectType project extra 1 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[1], $extra[2], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 11 objectType project extra 11 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[1], $extra[3], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 11 objectType project extra 101 orderBy id_desc
r($testreport->getListTest($objectID[1], $objectType[1], $extra[0], $orderBy[1])) && p() && e('1,11,21'); // 查询objectID 11 objectType project extra 0 orderBy id_asc

r($testreport->getListTest($objectID[2], $objectType[0], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 101 objectType product extra 0 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[1], $extra[0], $orderBy[0])) && p() && e('0');       // 查询objectID 101 objectType project extra 0 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[2], $extra[0], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 101 objectType execution extra 0 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[2], $extra[1], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 101 objectType execution extra 1 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[2], $extra[2], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 101 objectType execution extra 11 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[2], $extra[3], $orderBy[0])) && p() && e('21,11,1'); // 查询objectID 101 objectType execution extra 101 orderBy id_desc
r($testreport->getListTest($objectID[2], $objectType[2], $extra[0], $orderBy[1])) && p() && e('1,11,21'); // 查询objectID 101 objectType execution extra 0 orderBy id_asc
