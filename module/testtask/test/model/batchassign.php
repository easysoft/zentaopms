#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testtask')->gen(10);
zenData('testrun')->loadYaml('testrun')->gen(4);

su('admin');

/**

title=测试 testtaskModel->batchAssign();
timeout=0
cid=19155

- 测试单参数为 0 返回 false。 @0
- 指派给参数为空返回 false。 @0
- 测试用例参数为空返回 false。 @0
- 测试用例参数没有关联到测试单返回 false。 @0
- 测试单不存在返回 false。 @0
- 批量指派测试单 1 中的用例 1 和用例 2 给 user1，并记录日志。
 - 属性cases @user1,user1,admin,admin
 - 第actions[0]条的objectType属性 @case
 - 第actions[0]条的objectID属性 @2
 - 第actions[0]条的action属性 @assigned
 - 第actions[0]条的extra属性 @user1
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的objectID属性 @1
 - 第actions[1]条的action属性 @assigned
 - 第actions[1]条的extra属性 @user1
- 批量指派测试单 1 中的用例 3 和用例 4 给 user2，并记录日志。
 - 属性cases @user1,user1,user2,user2
 - 第actions[0]条的objectType属性 @case
 - 第actions[0]条的objectID属性 @4
 - 第actions[0]条的action属性 @assigned
 - 第actions[0]条的extra属性 @user2
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的objectID属性 @3
 - 第actions[1]条的action属性 @assigned
 - 第actions[1]条的extra属性 @user2
- 批量指派测试单 1 中的用例 1 和用例 3 给 user3，并记录日志。
 - 属性cases @user3,user1,user3,user2
 - 第actions[0]条的objectType属性 @case
 - 第actions[0]条的objectID属性 @3
 - 第actions[0]条的action属性 @assigned
 - 第actions[0]条的extra属性 @user3
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的objectID属性 @1
 - 第actions[1]条的action属性 @assigned
 - 第actions[1]条的extra属性 @user3
- 批量指派测试单 1 中的用例 2 和用例 4 给 user4，并记录日志。
 - 属性cases @user3,user4,user3,user4
 - 第actions[0]条的objectType属性 @case
 - 第actions[0]条的objectID属性 @4
 - 第actions[0]条的action属性 @assigned
 - 第actions[0]条的extra属性 @user4
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的objectID属性 @2
 - 第actions[1]条的action属性 @assigned
 - 第actions[1]条的extra属性 @user4
- 批量指派测试单 1 中的用例 1 和用例 5 给 user5，并记录日志。
 - 属性cases @user5,user4,user3,user4
 - 第actions[0]条的objectType属性 @case
 - 第actions[0]条的objectID属性 @1
 - 第actions[0]条的action属性 @assigned
 - 第actions[0]条的extra属性 @user5

*/

$testtask = new testtaskModelTest();

r($testtask->batchAssignTest(0, 'a', array(1))) && p() && e(0); // 测试单参数为 0 返回 false。
r($testtask->batchAssignTest(1, '',  array(1))) && p() && e(0); // 指派给参数为空返回 false。
r($testtask->batchAssignTest(1, 'a', array()))  && p() && e(0); // 测试用例参数为空返回 false。
r($testtask->batchAssignTest(1, 'a', array(5))) && p() && e(0); // 测试用例参数没有关联到测试单返回 false。
r($testtask->batchAssignTest(2, 'a', array(1))) && p() && e(0); // 测试单不存在返回 false。

r($testtask->batchAssignTest(1, 'user1', array(1, 2))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('user1,user1,admin,admin;case|2|assigned|user1;case|1|assigned|user1'); // 批量指派测试单 1 中的用例 1 和用例 2 给 user1，并记录日志。
r($testtask->batchAssignTest(1, 'user2', array(3, 4))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('user1,user1,user2,user2;case|4|assigned|user2;case|3|assigned|user2'); // 批量指派测试单 1 中的用例 3 和用例 4 给 user2，并记录日志。
r($testtask->batchAssignTest(1, 'user3', array(1, 3))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('user3,user1,user3,user2;case|3|assigned|user3;case|1|assigned|user3'); // 批量指派测试单 1 中的用例 1 和用例 3 给 user3，并记录日志。
r($testtask->batchAssignTest(1, 'user4', array(2, 4))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('user3,user4,user3,user4;case|4|assigned|user4;case|2|assigned|user4'); // 批量指派测试单 1 中的用例 2 和用例 4 给 user4，并记录日志。
r($testtask->batchAssignTest(1, 'user5', array(1, 5))) && p('cases;actions[0]:objectType|objectID|action|extra', '|') && e('user5,user4,user3,user4;case|1|assigned|user5'); // 批量指派测试单 1 中的用例 1 和用例 5 给 user5，并记录日志。