#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->gen('10');
zenData('actionrecent')->gen('0');
zenData('user')->gen(10);

su('admin');

/**

title=测试 actionModel->getFirstAction();
timeout=0
cid=14900

- 测试获取对象类型 story 对象ID 1 的动态信息
 - 属性id @1
 - 属性objectID @1
 - 属性objectType @product
 - 属性action @common
 - 属性extra @1
- 没有动态数据 @0

*/

$action = new actionModelTest();

r($action->getFirstActionTest())  && p('id,objectID,objectType,action,extra') && e('1,1,product,common,1'); // 测试获取对象类型 story 对象ID 1 的动态信息

$action->instance->dao->delete()->from(TABLE_ACTION)->exec();
r($action->getFirstActionTest())  && p() && e('0'); // 没有动态数据
