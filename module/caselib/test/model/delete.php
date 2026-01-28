#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testsuite')->gen(5);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->delete();
timeout=0
cid=15528

- 测试删除之后用例库 1 deleted值是否为1属性deleted @1
- 测试删除之后用例库 2 deleted值是否为1属性deleted @1
- 测试删除之后用例库 3 deleted值是否为1属性deleted @1
- 测试删除之后用例库 4 deleted值是否为1属性deleted @1
- 测试删除之后用例库 5 deleted值是否为1属性deleted @1

*/

$caselibIdList = array(1, 2, 3, 4, 5);

$caselib = new caselibModelTest();

r($caselib->deleteTest($caselibIdList[0])) && p('deleted') && e('1');  // 测试删除之后用例库 1 deleted值是否为1
r($caselib->deleteTest($caselibIdList[1])) && p('deleted') && e('1');  // 测试删除之后用例库 2 deleted值是否为1
r($caselib->deleteTest($caselibIdList[2])) && p('deleted') && e('1');  // 测试删除之后用例库 3 deleted值是否为1
r($caselib->deleteTest($caselibIdList[3])) && p('deleted') && e('1');  // 测试删除之后用例库 4 deleted值是否为1
r($caselib->deleteTest($caselibIdList[4])) && p('deleted') && e('1');  // 测试删除之后用例库 5 deleted值是否为1