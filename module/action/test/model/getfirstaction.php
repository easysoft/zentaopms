#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->gen('10');
zdTable('user')->gen(10);

su('admin');

/**

title=测试 actionModel->getFirstAction();
timeout=0
cid=1

- 测试获取对象类型 story 对象ID 1 的动态信息
 - 属性id @1
 - 属性objectID @1
 - 属性objectType @product

*/

$action = new actionTest();

r($action->getFirstActionTest())  && p('id,objectID,objectType') && e('1,1,product'); // 测试获取对象类型 story 对象ID 1 的动态信息