#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->gen(10);

/**

title=测试 actionModel->processDynamicForAPI();
timeout=0
cid=1

- 测试处理空数据 @0
- 处理所有动态
 - 第actor条的id属性 @1
 - 第actor条的account属性 @admin
 - 第actor条的realname属性 @admin

*/

$action = new actionTest();

$dynamics = $tester->dao->select('*')->from(TABLE_ACTION)->fetchAll();

r($action->processDynamicForAPITest(array()))   && p()                            && e('0');              // 测试处理空数据
r($action->processDynamicForAPITest($dynamics)) && p('actor:id,account,realname') && e('1,admin,admin');  // 处理所有动态