#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testsuite.class.php';
su('admin');

/**

title=测试 testsuiteModel->getNotImportedCases();
cid=1
pid=1

测试productID值为1,libID值为0,orderBy值为id_desc >> 0
测试productID值为1,libID值为0,orderBy值为id_asc >> 0
测试productID值为1,libID值为0,orderBy值为title_desc,id_desc >> 0
测试productID值为1,libID值为0,orderBy值为title_asc,id_desc >> 0
测试productID值为0,libID值为0,orderBy值为id_desc >> 0
测试productID值为0,libID值为0,orderBy值为id_asc >> 0
测试productID值为0,libID值为0,orderBy值为title_desc,id_desc >> 0
测试productID值为0,libID值为0,orderBy值为title_asc,id_desc >> 0
测试productID值为1,libID值为201,orderBy值为id_desc >> 410,用例库用例10;409,用例库用例9
测试productID值为1,libID值为201,orderBy值为id_asc >> 401,用例库用例1;402,用例库用例2
测试productID值为1,libID值为201,orderBy值为title_desc,id_desc >> 409,用例库用例9;408,用例库用例8
测试productID值为1,libID值为201,orderBy值为title_asc,id_desc >> 401,用例库用例1;410,用例库用例10
测试productID值为0,libID值为201,orderBy值为id_desc >> 410,用例库用例10;409,用例库用例9
测试productID值为0,libID值为201,orderBy值为id_asc >> 401,用例库用例1;402,用例库用例2
测试productID值为0,libID值为201,orderBy值为title_desc,id_desc >> 409,用例库用例9;408,用例库用例8
测试productID值为0,libID值为201,orderBy值为title_asc,id_desc >> 401,用例库用例1;410,用例库用例10

*/
$productID = array(1, 0);
$libID = array(201, 0);
$orderBy = array('id_desc', 'id_asc', 'title_desc,id_desc', 'title_asc,id_desc');

$testsuite = new testsuiteTest();

r($testsuite->getNotImportedCasesTest($productID[0], $libID[1], $orderBy[0])) && p()                            && e('0');                                 //测试productID值为1,libID值为0,orderBy值为id_desc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[1], $orderBy[1])) && p()                            && e('0');                                 //测试productID值为1,libID值为0,orderBy值为id_asc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[1], $orderBy[2])) && p()                            && e('0');                                 //测试productID值为1,libID值为0,orderBy值为title_desc,id_desc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[1], $orderBy[3])) && p()                            && e('0');                                 //测试productID值为1,libID值为0,orderBy值为title_asc,id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[1], $orderBy[0])) && p()                            && e('0');                                 //测试productID值为0,libID值为0,orderBy值为id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[1], $orderBy[1])) && p()                            && e('0');                                 //测试productID值为0,libID值为0,orderBy值为id_asc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[1], $orderBy[2])) && p()                            && e('0');                                 //测试productID值为0,libID值为0,orderBy值为title_desc,id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[1], $orderBy[3])) && p()                            && e('0');                                 //测试productID值为0,libID值为0,orderBy值为title_asc,id_desc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[0], $orderBy[0])) && p('410:id,title;409:id,title') && e('410,用例库用例10;409,用例库用例9');  //测试productID值为1,libID值为201,orderBy值为id_desc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[0], $orderBy[1])) && p('401:id,title;402:id,title') && e('401,用例库用例1;402,用例库用例2');   //测试productID值为1,libID值为201,orderBy值为id_asc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[0], $orderBy[2])) && p('409:id,title;408:id,title') && e('409,用例库用例9;408,用例库用例8');   //测试productID值为1,libID值为201,orderBy值为title_desc,id_desc
r($testsuite->getNotImportedCasesTest($productID[0], $libID[0], $orderBy[3])) && p('401:id,title;410:id,title') && e('401,用例库用例1;410,用例库用例10');  //测试productID值为1,libID值为201,orderBy值为title_asc,id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[0], $orderBy[0])) && p('410:id,title;409:id,title') && e('410,用例库用例10;409,用例库用例9');  //测试productID值为0,libID值为201,orderBy值为id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[0], $orderBy[1])) && p('401:id,title;402:id,title') && e('401,用例库用例1;402,用例库用例2');   //测试productID值为0,libID值为201,orderBy值为id_asc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[0], $orderBy[2])) && p('409:id,title;408:id,title') && e('409,用例库用例9;408,用例库用例8');   //测试productID值为0,libID值为201,orderBy值为title_desc,id_desc
r($testsuite->getNotImportedCasesTest($productID[1], $libID[0], $orderBy[3])) && p('401:id,title;410:id,title') && e('401,用例库用例1;410,用例库用例10');  //测试productID值为0,libID值为201,orderBy值为title_asc,id_desc