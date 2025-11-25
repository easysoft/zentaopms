#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

/**

title=测试 todoModel->getByList();
timeout=0
cid=19258

- 执行todo模块的getByListTest方法，参数是$todoIDList1  @1
- 执行todo模块的getByListTest方法，参数是$todoIDList2  @1234
- 执行todo模块的getByListTest方法，参数是$todoIDList3  @5
- 执行todo模块的getByListTest方法，参数是$todoIDList4  @
- 执行todo模块的getByListTest方法，参数是$todoIDList5  @12345

*/

$todoIDList1 = array('1');
$todoIDList2 = array('1', '2', '3', '4');
$todoIDList3 = array('5', '6', '7', '8');
$todoIDList4 = array('9', '10', '11', '12'); /* Test not existed items. */
$todoIDList5 = array(); /* Test empty todoIDList. */

$todo = new todoTest();

r($todo->getByListTest($todoIDList1)) && p() && e('1');
r($todo->getByListTest($todoIDList2)) && p() && e('1234');
r($todo->getByListTest($todoIDList3)) && p() && e('5');
r($todo->getByListTest($todoIDList4)) && p() && e('');
r($todo->getByListTest($todoIDList5)) && p() && e('12345');
