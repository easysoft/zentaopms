#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('testsuite')->loadYaml('testsuite')->gen(9);

/**

title=测试 testsuiteModel->getUnitSuites();
timeout=0
cid=19146

- 测试productID值为1,orderBy为id_desc @0
- 测试productID值为1,orderBy为id_desc
 - 第9条的name属性 @这是测试套件名称9
 - 第3条的name属性 @这是测试套件名称3
- 测试productID值为1,orderBy为id_asc
 - 第3条的name属性 @这是测试套件名称3
 - 第9条的name属性 @这是测试套件名称9
- 测试productID值为1,orderBy为name_desc,id_desc
 - 第9条的name属性 @这是测试套件名称9
 - 第3条的name属性 @这是测试套件名称3
- 测试productID值为1,orderBy为name_asc,id_desc
 - 第3条的name属性 @这是测试套件名称3
 - 第9条的name属性 @这是测试套件名称9

*/
$productID = array(1, 0);
$orderBy   = array('id_desc', 'id_asc', 'name_desc,id_desc', 'name_asc,id_desc');

$testsuite = new testsuiteModelTest();

r($testsuite->getUnitSuitesTest($productID[0], $orderBy[0])) && p()                && e('0');                                    //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[0])) && p('9:name;3:name') && e('这是测试套件名称9;这是测试套件名称3');  //测试productID值为1,orderBy为id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[1])) && p('3:name;9:name') && e('这是测试套件名称3;这是测试套件名称9');  //测试productID值为1,orderBy为id_asc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[2])) && p('9:name;3:name') && e('这是测试套件名称9;这是测试套件名称3');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getUnitSuitesTest($productID[1], $orderBy[3])) && p('3:name;9:name') && e('这是测试套件名称3;这是测试套件名称9');  //测试productID值为1,orderBy为name_asc,id_desc
