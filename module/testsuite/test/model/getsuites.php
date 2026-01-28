#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testsuite')->loadYaml('testsuite')->gen(4);
zenData('user')->gen(2);

su('admin');

/**

title=测试 testsuiteModel->getSuites();
timeout=0
cid=19145

- 测试productID值为1,orderBy为id_desc
 - 第3条的name属性 @这是测试套件名称3
 - 第1条的name属性 @这是测试套件名称1
 - 属性2 @~~
- 测试productID值为1,orderBy为id_asc
 - 第1条的name属性 @这是测试套件名称1
 - 第3条的name属性 @这是测试套件名称3
 - 属性2 @~~
- 测试productID值为1,orderBy为name_desc,id_desc
 - 第1条的name属性 @这是测试套件名称1
 - 第3条的name属性 @这是测试套件名称3
 - 属性2 @~~
- 测试productID值为1,orderBy为name_asc,id_desc
 - 第3条的name属性 @这是测试套件名称3
 - 第1条的name属性 @这是测试套件名称1
 - 属性2 @~~
- 测试productID值为1,orderBy为id_desc @0
- 测试productID值为1,orderBy为id_desc属性2 @~~
- 测试productID值为1,orderBy为id_asc属性2 @~~
- 测试productID值为1,orderBy为name_desc,id_desc
 - 第1条的name属性 @这是测试套件名称1
 - 第3条的name属性 @这是测试套件名称3

*/
$productID = array(1, 0);
$orderBy   = array('id_desc', 'id_asc', 'name_desc,id_desc', 'name_asc,id_desc');

$testsuite = new testsuiteModelTest();

r($testsuite->getSuitesTest($productID[0], $orderBy[0], null, '')) && p('3:name;1:name;2') && e('这是测试套件名称3;这是测试套件名称1;~~');  //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitesTest($productID[0], $orderBy[1], null, '')) && p('1:name;3:name;2') && e('这是测试套件名称1;这是测试套件名称3;~~');  //测试productID值为1,orderBy为id_asc
r($testsuite->getSuitesTest($productID[0], $orderBy[2], null, '')) && p('1:name;3:name;2') && e('这是测试套件名称1;这是测试套件名称3;~~');  //测试productID值为1,orderBy为name_desc,id_desc
r($testsuite->getSuitesTest($productID[0], $orderBy[3], null, '')) && p('3:name;1:name;2') && e('这是测试套件名称3;这是测试套件名称1;~~');  //测试productID值为1,orderBy为name_asc,id_desc

su('user1');

r($testsuite->getSuitesTest($productID[1], $orderBy[0], null, '')) && p()                && e('0');                                    //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitesTest($productID[0], $orderBy[0], null, '')) && p('2')             && e('~~');                                   //测试productID值为1,orderBy为id_desc
r($testsuite->getSuitesTest($productID[0], $orderBy[1], null, '')) && p('2')             && e('~~');                                   //测试productID值为1,orderBy为id_asc
r($testsuite->getSuitesTest($productID[0], $orderBy[2], null, '')) && p('1:name;3:name') && e('这是测试套件名称1;这是测试套件名称3');  //测试productID值为1,orderBy为name_desc,id_desc
