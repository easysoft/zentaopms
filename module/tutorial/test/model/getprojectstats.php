#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getProjectStats();

- 测试 admin 登录时是否能拿到 browseType 空 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType 空 数据 teamMembers第2[teamMembers]条的0属性 @admin
- 测试 admin 登录时是否能拿到 browseType all 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType all 数据 teamMembers第2[teamMembers]条的0属性 @admin
- 测试 admin 登录时是否能拿到 browseType noclosed 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project-noclosed
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType noclosed 数据 teamMembers第2[teamMembers]条的0属性 @admin
- 测试 user1 登录时是否能拿到 browseType 空 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType 空 数据 teamMembers第2[teamMembers]条的0属性 @user1
- 测试 user1 登录时是否能拿到 browseType all 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType all 数据 teamMembers第2[teamMembers]条的0属性 @user1
- 测试 user1 登录时是否能拿到 browseType noclosed 数据
 - 第2条的id属性 @2
 - 第2条的name属性 @Test Project-noclosed
 - 第2条的model属性 @scrum
 - 第2条的left属性 @0
 - 第2条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType noclosed 数据 teamMembers第2[teamMembers]条的0属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

$tutorial = new tutorialTest();

$browseType = array('', 'all', 'noclosed');

su('admin');
r($tutorial->getProjectStatsTest($browseType['0'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project,scrum,0,1');          // 测试 admin 登录时是否能拿到 browseType 空 数据
r($tutorial->getProjectStatsTest($browseType['0'])) && p('2[teamMembers]:0')               && e('admin');                             // 测试 admin 登录时是否能拿到 browseType 空 数据 teamMembers
r($tutorial->getProjectStatsTest($browseType['1'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project,scrum,0,1');          // 测试 admin 登录时是否能拿到 browseType all 数据
r($tutorial->getProjectStatsTest($browseType['1'])) && p('2[teamMembers]:0')               && e('admin');                             // 测试 admin 登录时是否能拿到 browseType all 数据 teamMembers
r($tutorial->getProjectStatsTest($browseType['2'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project-noclosed,scrum,0,1'); // 测试 admin 登录时是否能拿到 browseType noclosed 数据
r($tutorial->getProjectStatsTest($browseType['2'])) && p('2[teamMembers]:0')               && e('admin');                             // 测试 admin 登录时是否能拿到 browseType noclosed 数据 teamMembers

su('user1');
r($tutorial->getProjectStatsTest($browseType['0'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project,scrum,0,1');          // 测试 user1 登录时是否能拿到 browseType 空 数据
r($tutorial->getProjectStatsTest($browseType['0'])) && p('2[teamMembers]:0')               && e('user1');                             // 测试 user1 登录时是否能拿到 browseType 空 数据 teamMembers
r($tutorial->getProjectStatsTest($browseType['1'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project,scrum,0,1');          // 测试 user1 登录时是否能拿到 browseType all 数据
r($tutorial->getProjectStatsTest($browseType['1'])) && p('2[teamMembers]:0')               && e('user1');                             // 测试 user1 登录时是否能拿到 browseType all 数据 teamMembers
r($tutorial->getProjectStatsTest($browseType['2'])) && p('2:id,name,model,left,teamCount') && e('2,Test Project-noclosed,scrum,0,1'); // 测试 user1 登录时是否能拿到 browseType noclosed 数据
r($tutorial->getProjectStatsTest($browseType['2'])) && p('2[teamMembers]:0')               && e('user1');                             // 测试 user1 登录时是否能拿到 browseType noclosed 数据 teamMembers
