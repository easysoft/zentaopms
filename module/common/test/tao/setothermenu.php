#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('user')->gen(10);

/**

title=测试 commonTao->setOtherMenu();
timeout=0
cid=1

- 查看admin是否可以打印看板空间的菜单
 -  @~~
 - 属性1 @space
- 查看user1是否可以打印文档首页的菜单
 -  @1
 - 属性1 @index
- 查看user1是否可以打印看板空间的菜单
 -  @~~
 - 属性1 @space
- 查看user1是否可以打印文档首页的菜单
 -  @~~
 - 属性1 @index

*/
$result1 = commonTao::setOtherMenu(false, 'kanban', 'space');
$result2 = commonTao::setOtherMenu(false, 'doc', 'index');

su('user1');
$result3 = commonTao::setOtherMenu(false, 'kanban', 'space');
$result4 = commonTao::setOtherMenu(false, 'doc', 'index');

r(1) && p() && e(1);
r($result1) && p('0,1') && e('~~,space'); // 查看admin是否可以打印看板空间的菜单
r($result2) && p('0,1') && e('1,index');  // 查看user1是否可以打印文档首页的菜单
r($result3) && p('0,1') && e('~~,space'); // 查看user1是否可以打印看板空间的菜单
r($result4) && p('0,1') && e('~~,index'); // 查看user1是否可以打印文档首页的菜单
