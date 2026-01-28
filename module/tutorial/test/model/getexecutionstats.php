#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 tutorialModel->getExecutionStats();
timeout=0
cid=19433

- 测试 admin 登录时是否能拿到 browseType 空 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType 空 数据 teamMembers第0[teamMembers]条的0属性 @admin
- 测试 admin 登录时是否能拿到 browseType all 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType all 数据 teamMembers第0[teamMembers]条的0属性 @admin
- 测试 admin 登录时是否能拿到 browseType noclosed 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution-noclosed
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 admin 登录时是否能拿到 browseType noclosed 数据 teamMembers第0[teamMembers]条的0属性 @admin
- 测试 user1 登录时是否能拿到 browseType 空 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType 空 数据 teamMembers第0[teamMembers]条的0属性 @user1
- 测试 user1 登录时是否能拿到 browseType all 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType all 数据 teamMembers第0[teamMembers]条的0属性 @user1
- 测试 user1 登录时是否能拿到 browseType noclosed 数据
 - 第0条的id属性 @3
 - 第0条的name属性 @Test execution-noclosed
 - 第0条的left属性 @0
 - 第0条的teamCount属性 @1
- 测试 user1 登录时是否能拿到 browseType noclosed 数据 teamMembers第0[teamMembers]条的0属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$tutorial = new tutorialModelTest();

$browseType = array('', 'all', 'noclosed');

su('admin');
r($tutorial->getExecutionStatsTest($browseType['0'])) && p('0:id,name,left,teamCount') && e('3,Test execution,0,1');             // 测试 admin 登录时是否能拿到 browseType 空 数据
r($tutorial->getExecutionStatsTest($browseType['0'])) && p('0[teamMembers]:0')         && e('admin');                             // 测试 admin 登录时是否能拿到 browseType 空 数据 teamMembers
r($tutorial->getExecutionStatsTest($browseType['1'])) && p('0:id,name,left,teamCount') && e('3,Test execution,0,1');             // 测试 admin 登录时是否能拿到 browseType all 数据
r($tutorial->getExecutionStatsTest($browseType['1'])) && p('0[teamMembers]:0')         && e('admin');                             // 测试 admin 登录时是否能拿到 browseType all 数据 teamMembers
r($tutorial->getExecutionStatsTest($browseType['2'])) && p('0:id,name,left,teamCount') && e('3,Test execution-noclosed,0,1'); // 测试 admin 登录时是否能拿到 browseType noclosed 数据
r($tutorial->getExecutionStatsTest($browseType['2'])) && p('0[teamMembers]:0')         && e('admin');                             // 测试 admin 登录时是否能拿到 browseType noclosed 数据 teamMembers

su('user1');
r($tutorial->getExecutionStatsTest($browseType['0'])) && p('0:id,name,left,teamCount') && e('3,Test execution,0,1');          // 测试 user1 登录时是否能拿到 browseType 空 数据
r($tutorial->getExecutionStatsTest($browseType['0'])) && p('0[teamMembers]:0')         && e('user1');                         // 测试 user1 登录时是否能拿到 browseType 空 数据 teamMembers
r($tutorial->getExecutionStatsTest($browseType['1'])) && p('0:id,name,left,teamCount') && e('3,Test execution,0,1');          // 测试 user1 登录时是否能拿到 browseType all 数据
r($tutorial->getExecutionStatsTest($browseType['1'])) && p('0[teamMembers]:0')         && e('user1');                         // 测试 user1 登录时是否能拿到 browseType all 数据 teamMembers
r($tutorial->getExecutionStatsTest($browseType['2'])) && p('0:id,name,left,teamCount') && e('3,Test execution-noclosed,0,1'); // 测试 user1 登录时是否能拿到 browseType noclosed 数据
r($tutorial->getExecutionStatsTest($browseType['2'])) && p('0[teamMembers]:0')         && e('user1');                         // 测试 user1 登录时是否能拿到 browseType noclosed 数据 teamMembers