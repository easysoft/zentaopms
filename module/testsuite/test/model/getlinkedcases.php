#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(2);
zenData('testsuite')->gen(1);
zenData('suitecase')->gen(2);
zenData('testresult')->gen(2);

/**

title=测试 testsuiteModel->getLinkedCases();
timeout=0
cid=19143

- 测试suiteID值正常存在,orderBy值为id_desc,append值为true
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @1
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @1
- 测试suiteID值正常存在,orderBy值为id_desc,append值为false
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @a
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @a
- 测试suiteID值正常存在,orderBy值为id_asc,append值为true
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @1
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @1
- 测试suiteID值正常存在,orderBy值为id_asc,append值为false
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @a
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @a
- 测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @1
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @1
- 测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @a
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @a
- 测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @1
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @1
- 测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false
 - 第1条的id属性 @1
 - 第1条的title属性 @这个是测试用例1
 - 第1条的results属性 @a
 - 第2条的id属性 @2
 - 第2条的title属性 @这个是测试用例2
 - 第2条的results属性 @a

 */

$suiteID = 1;
$orderBy = array('id_desc', 'id_asc', 'title_desc,id_desc', 'title_asc,id_desc');
$append  = array(true, false);

$testsuite = new testsuiteModelTest();

r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], null, $append[0])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,1;1,这个是测试用例1,1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[0], null, $append[1])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,a;1,这个是测试用例1,a');  //测试suiteID值正常存在,orderBy值为id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], null, $append[0])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,1;2,这个是测试用例2,1');  //测试suiteID值正常存在,orderBy值为id_asc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[1], null, $append[1])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,a;2,这个是测试用例2,a');  //测试suiteID值正常存在,orderBy值为id_asc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], null, $append[0])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,1;1,这个是测试用例1,1');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[2], null, $append[1])) && p('2:id,title,results;1:id,title,results') && e('2,这个是测试用例2,a;1,这个是测试用例1,a');  //测试suiteID值正常存在,orderBy值为title_desc,id_desc,append值为false
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], null, $append[0])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,1;2,这个是测试用例2,1');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为true
r($testsuite->getLinkedCasesTest($suiteID, $orderBy[3], null, $append[1])) && p('1:id,title,results;2:id,title,results') && e('1,这个是测试用例1,a;2,这个是测试用例2,a');  //测试suiteID值正常存在,orderBy值为title_asc,id_desc,append值为false
