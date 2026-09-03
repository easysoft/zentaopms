#!/usr/bin/env php
<?php

/**

title=测试 searchModel->setCondition();
timeout=0
cid=18346

- 测试等于某天的条件 @ and (`createdDate` >= '2023-12-08' AND `createdDate` <= '2023-12-08 23:59:59')
- 测试不等于某天的条件 @ or (`createdDate` < '2023-12-08' OR `createdDate` > '2023-12-08 23:59:59')
- 测试小于等于某天的条件 @ and `createdDate` <= '2023-12-08 23:59:59'
- 测试大于某天的条件 @ or `createdDate` > '2023-12-08 23:59:59'
- 测试其他 @ and `title`  LIKE '%test%'
- 测试下拉单选等于 @ and `status` = 'active' 
- 测试下拉多选等于 @ and (`status` = 'active' OR `status` = 'closed' OR `status` = 'wait')
- 测试下拉多选不等于 @ and (`status` != 'active' AND `status` != 'closed' AND `status` != 'wait')
- 测试下拉多选其他操作符 @ and (1 = 0)
- 测试输入框逗号值不按多选处理 @ and `title` = 'a,b,c' 

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$fields    = array('createdDate', 'title');
$operators = array('=', '!=', '<=', '>', 'include');
$values    = array('2023-12-08', 'test');
$andOrs    = array('and', 'or');

$search = new searchTaoTest();
r($search->setWhereTest($fields[0], $operators[0], $values[0], $andOrs[0])) && p() && e(" and (`createdDate` >= '2023-12-08' AND `createdDate` <= '2023-12-08 23:59:59')"); //测试等于某天的条件
r($search->setWhereTest($fields[0], $operators[1], $values[0], $andOrs[1])) && p() && e(" or (`createdDate` < '2023-12-08' OR `createdDate` > '2023-12-08 23:59:59')");     //测试不等于某天的条件
r($search->setWhereTest($fields[0], $operators[2], $values[0], $andOrs[0])) && p() && e(" and `createdDate` <= '2023-12-08 23:59:59'");                                     //测试小于等于某天的条件
r($search->setWhereTest($fields[0], $operators[3], $values[0], $andOrs[1])) && p() && e(" or `createdDate` > '2023-12-08 23:59:59'");                                       //测试大于某天的条件
r($search->setWhereTest($fields[1], $operators[4], $values[1], $andOrs[0])) && p() && e(" and `title`  LIKE '%test%'");                                                     //测试其他
r($search->setWhereTest('status', '=', 'active', 'and', 'select')) && p() && e(" and `status` = 'active' ");                                                               //测试下拉单选等于
r($search->setWhereTest('status', '=', 'active,closed,wait', 'and', 'select')) && p() && e(" and (`status` = 'active' OR `status` = 'closed' OR `status` = 'wait')");     //测试下拉多选等于
r($search->setWhereTest('status', '!=', 'active,closed,wait', 'and', 'select')) && p() && e(" and (`status` != 'active' AND `status` != 'closed' AND `status` != 'wait')"); //测试下拉多选不等于
r($search->setWhereTest('status', 'include', 'active,closed', 'and', 'select')) && p() && e(" and (1 = 0)");                                                              //测试下拉多选其他操作符
r($search->setWhereTest('title', '=', 'a,b,c', 'and', 'input')) && p() && e(" and `title` = 'a,b,c' ");                                                                    //测试输入框逗号值不按多选处理
