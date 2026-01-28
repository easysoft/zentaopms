#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('case')->gen(10);
zenData('testsuite')->gen(10);
zenData('suitecase')->gen(10);

/**

title=测试 testsuiteModel->getLinkedCasePairs();
timeout=0
cid=19142

- 测试suiteID值正常存在,orderBy值为id_desc,append值为true属性1 @0
- 测试suiteID值正常存在,orderBy值为id_desc,append值为false
 - 属性2 @这个是测试用例2
 - 属性1 @这个是测试用例1
- 测试suiteID值正常存在,orderBy值为id_desc,append值为true
 - 属性4 @这个是测试用例4
 - 属性3 @这个是测试用例3

*/
$suiteID = array(0, 1, 2);

$testsuite = new testsuiteModelTest();

r($testsuite->getLinkedCasePairsTest($suiteID[0])) && p('1')   && e('0'); //测试suiteID值正常存在,orderBy值为id_desc,append值为true
r($testsuite->getLinkedCasePairsTest($suiteID[1])) && p('2;1') && e('这个是测试用例2;这个是测试用例1');  //测试suiteID值正常存在,orderBy值为id_desc,append值为false
r($testsuite->getLinkedCasePairsTest($suiteID[2])) && p('4;3') && e('这个是测试用例4;这个是测试用例3');  //测试suiteID值正常存在,orderBy值为id_desc,append值为true