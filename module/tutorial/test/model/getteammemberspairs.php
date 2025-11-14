#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getTeamMembersPairs();
timeout=0
cid=19487

- 测试是否能拿到数据 admin属性admin @admin
- 测试是否能拿到数据 空属性~~ @~~
- 测试是否能拿到数据 user1属性user1 @用户1
- 测试是否能拿到数据 user2属性user2 @用户2
- 测试是否能拿到数据 user3属性user3 @用户3
- 测试是否能拿到数据 user4属性user4 @用户4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

zenData('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getTeamMembersPairsTest()) && p('admin') && e('admin'); // 测试是否能拿到数据 admin
r($tutorial->getTeamMembersPairsTest()) && p('~~')    && e('~~');    // 测试是否能拿到数据 空

su('user1');
r($tutorial->getTeamMembersPairsTest()) && p('user1') && e('用户1'); // 测试是否能拿到数据 user1

su('user2');
r($tutorial->getTeamMembersPairsTest()) && p('user2') && e('用户2'); // 测试是否能拿到数据 user2

su('user3');
r($tutorial->getTeamMembersPairsTest()) && p('user3') && e('用户3'); // 测试是否能拿到数据 user3

su('user4');
r($tutorial->getTeamMembersPairsTest()) && p('user4') && e('用户4'); // 测试是否能拿到数据 user4