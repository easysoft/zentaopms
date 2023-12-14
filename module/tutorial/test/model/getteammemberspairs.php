#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getTeamMembersPairs();
cid=1

- 测试是否能拿到数据 admin属性admin @admin
- 测试是否能拿到数据 空属性`` @``
- 测试是否能拿到数据 user1属性user1 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getTeamMembersPairsTest()) && p('admin') && e('admin'); // 测试是否能拿到数据 admin
r($tutorial->getTeamMembersPairsTest()) && p('``')    && e('``');    // 测试是否能拿到数据 空

su('user1');
r($tutorial->getTeamMembersPairsTest()) && p('user1') && e('user1'); // 测试是否能拿到数据 user1
