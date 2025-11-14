#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);

/**

title=测试 commonTao->setAssetLibMenu();
timeout=0
cid=0

- 查看admin是否可以打印资产库的菜单
 -  @1
 - 属性1 @caselib
- 查看user1是否可以打印资产库的菜单
 -  @~~
 - 属性1 @browse
- 查看user1是否可以打印资产库的菜单
 -  @~~
 - 属性1 @create

*/
$result1 = commonTao::setAssetLibMenu(false, 'assetlib', 'browse');

su('user1');
$result2 = commonTao::setAssetLibMenu(false, 'assetlib', 'browse');
$result3 = commonTao::setAssetLibMenu(false, 'assetlib', 'create');

r($result1) && p('0,1') && e('1,caselib'); // 查看admin是否可以打印资产库的菜单
r($result2) && p('0,1') && e('~~,browse'); // 查看user1是否可以打印资产库的菜单
r($result3) && p('0,1') && e('~~,create'); // 查看user1是否可以打印资产库的菜单
