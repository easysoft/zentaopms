#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDataOfClosedBugsPerUser();
timeout=0
cid=15375

- 测试步骤1：测试admin用户关闭bug的数量第admin条的value属性 @8
- 测试步骤2：测试user1用户关闭bug的数量第user1条的value属性 @5
- 测试步骤3：测试user2用户关闭bug的数量第user2条的value属性 @3
- 测试步骤4：测试用户名称显示正确第admin条的name属性 @admin
- 测试步骤5：测试用户名称转换正确第user1条的name属性 @用户1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('bug')->loadYaml('closedby')->gen(20);

su('admin');

$bug = new bugModelTest();

r($bug->getDataOfClosedBugsPerUserTest()) && p('admin:value') && e('8'); // 测试步骤1：测试admin用户关闭bug的数量
r($bug->getDataOfClosedBugsPerUserTest()) && p('user1:value') && e('5'); // 测试步骤2：测试user1用户关闭bug的数量
r($bug->getDataOfClosedBugsPerUserTest()) && p('user2:value') && e('3'); // 测试步骤3：测试user2用户关闭bug的数量
r($bug->getDataOfClosedBugsPerUserTest()) && p('admin:name') && e('admin'); // 测试步骤4：测试用户名称显示正确
r($bug->getDataOfClosedBugsPerUserTest()) && p('user1:name') && e('用户1'); // 测试步骤5：测试用户名称转换正确