#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuite.unittest.class.php';

zenData('testsuite')->loadYaml('testsuite')->gen(3);
zenData('user')->gen(2);

su('admin');

/**

title=测试 testsuiteModel->getSuitePairs();
timeout=0
cid=19144

- 测试productID值为1,orderBy为id_desc属性1 @这是测试套件名称1
- 测试productID值为1,orderBy为id_asc属性3 @这是测试套件名称3
- 测试productID值为1,orderBy为name_desc,id_desc属性1 @这是测试套件名称1
- 测试productID值为1,orderBy为name_asc,id_desc属性3 @这是测试套件名称3
- 测试productID值为1,orderBy为id_desc @0
- 测试productID值为1,orderBy为id_asc @0
- 测试productID值为1,orderBy为name_desc,id_desc @0
- 测试productID值为1,orderBy为name_asc,id_desc @0
- 测试productID值为1,orderBy为id_desc属性2 @~~
- 测试productID值为1,orderBy为id_desc属性1 @这是测试套件名称1

 */

$productID = array(1, 0, 2);
$orderBy   = array('id_desc', 'id_asc', 'name_desc,id_desc', 'name_asc,id_desc');

$testsuite = new testsuiteTest();

r($testsuite->getSuitePairsTest($productID[0], $orderBy[0])) && p('1') && e('这是测试套件名称1');  //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitePairsTest($productID[0], $orderBy[1])) && p('3') && e('这是测试套件名称3');  //测试productID值为1,orderBy为id_asc
r($testsuite->getSuitePairsTest($productID[0], $orderBy[2])) && p('1') && e('这是测试套件名称1');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getSuitePairsTest($productID[0], $orderBy[3])) && p('3') && e('这是测试套件名称3');  //测试productID值为1,orderBy为name_asc,id_desc
r($testsuite->getSuitePairsTest($productID[1], $orderBy[0])) && p()    && e('0');                  //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitePairsTest($productID[1], $orderBy[1])) && p()    && e('0');                  //测试productID值为1,orderBy为id_asc
r($testsuite->getSuitePairsTest($productID[1], $orderBy[2])) && p()    && e('0');                  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getSuitePairsTest($productID[1], $orderBy[3])) && p()    && e('0');                  //测试productID值为1,orderBy为name_asc,id_desc

su('user1');

r($testsuite->getSuitePairsTest($productID[0], $orderBy[2])) && p('2') && e('~~');                 //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitePairsTest($productID[0], $orderBy[2])) && p('1') && e('这是测试套件名称1');  //测试productID值为1,orderBy为id_desc
