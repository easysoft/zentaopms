#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getProjectStats();
timeout=0
cid=1

- 测试是否能拿到数据第2条的model属性 @scrum
- 测试是否能拿到数据第2条的left属性 @0
- 测试是否能拿到数据第2[teamMembers]条的0属性 @admin

*/

$tutorial = new tutorialTest();

r($tutorial->getProjectStatsTest()) && p('2:model')          && e('scrum'); //测试是否能拿到数据
r($tutorial->getProjectStatsTest()) && p('2:left')           && e('0');     //测试是否能拿到数据
r($tutorial->getProjectStatsTest()) && p('2[teamMembers]:0') && e('admin'); //测试是否能拿到数据