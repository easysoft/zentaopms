#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

zdTable('bug')->gen(100);
zdTable('build')->gen(100);
zdTable('release')->gen(100);
zdTable('branch')->gen(100);
zdTable('product')->gen(100);
zdTable('project')->config('execution')->gen(50);

/**

title=bugModel->processBuildForBugs();
cid=1
pid=1

- 测试处理bug1 2 3的openedBuild resolvedBuild字段
 - 第1条的openedBuild属性 @项目11版本1
 - 第1条的resolvedBuild属性 @~~
 - 第2条的openedBuild属性 @0
 - 第2条的resolvedBuild属性 @~~
 - 第3条的openedBuild属性 @项目11版本1
 - 第3条的resolvedBuild属性 @~~

- 测试处理bug4 5 6的openedBuild resolvedBuild字段
 - 第4条的openedBuild属性 @主干
 - 第4条的resolvedBuild属性 @~~
 - 第5条的openedBuild属性 @主干
 - 第5条的resolvedBuild属性 @~~
 - 第6条的openedBuild属性 @主干
 - 第6条的resolvedBuild属性 @~~

- 测试处理bug51 52 53的openedBuild resolvedBuild字段
 - 第51条的openedBuild属性 @主干
 - 第51条的resolvedBuild属性 @~~
 - 第52条的openedBuild属性 @主干
 - 第52条的resolvedBuild属性 @~~
 - 第53条的openedBuild属性 @主干
 - 第53条的resolvedBuild属性 @~~

- 测试处理bug54 55 56的openedBuild resolvedBuild字段
 - 第54条的openedBuild属性 @主干
 - 第54条的resolvedBuild属性 @~~
 - 第55条的openedBuild属性 @主干
 - 第55条的resolvedBuild属性 @~~
 - 第56条的openedBuild属性 @主干
 - 第56条的resolvedBuild属性 @主干

- 测试处理bug81 82 83的openedBuild resolvedBuild字段
 - 第81条的openedBuild属性 @主干
 - 第81条的resolvedBuild属性 @~~
 - 第82条的openedBuild属性 @主干
 - 第82条的resolvedBuild属性 @~~
 - 第83条的openedBuild属性 @主干
 - 第83条的resolvedBuild属性 @~~

- 测试处理bug84 85 86的openedBuild resolvedBuild字段
 - 第84条的openedBuild属性 @主干
 - 第84条的resolvedBuild属性 @~~
 - 第85条的openedBuild属性 @主干
 - 第85条的resolvedBuild属性 @~~
 - 第86条的openedBuild属性 @主干
 - 第86条的resolvedBuild属性 @~~

*/

$bugIDList1 = array('1', '2', '3');
$bugIDList2 = array('4', '5', '6');
$bugIDList3 = array('51', '52', '53');
$bugIDList4 = array('54', '55', '56');
$bugIDList5 = array('81', '82', '83');
$bugIDList6 = array('84', '85', '86');

$bug = new bugTest();
r($bug->processBuildForBugsTest($bugIDList1)) && p('1:openedBuild,resolvedBuild;2:openedBuild,resolvedBuild;3:openedBuild,resolvedBuild')    && e('项目11版本1,~~,0,~~,项目11版本1,~~'); // 测试处理bug1 2 3的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList2)) && p('4:openedBuild,resolvedBuild;5:openedBuild,resolvedBuild;6:openedBuild,resolvedBuild')    && e('主干,~~,主干,~~,主干,~~');            // 测试处理bug4 5 6的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList3)) && p('51:openedBuild,resolvedBuild;52:openedBuild,resolvedBuild;53:openedBuild,resolvedBuild') && e('主干,~~,主干,~~,主干,~~');            // 测试处理bug51 52 53的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList4)) && p('54:openedBuild,resolvedBuild;55:openedBuild,resolvedBuild;56:openedBuild,resolvedBuild') && e('主干,~~,主干,~~,主干,主干');          // 测试处理bug54 55 56的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList5)) && p('81:openedBuild,resolvedBuild;82:openedBuild,resolvedBuild;83:openedBuild,resolvedBuild') && e('主干,~~,主干,~~,主干,~~');            // 测试处理bug81 82 83的openedBuild resolvedBuild字段
r($bug->processBuildForBugsTest($bugIDList6)) && p('84:openedBuild,resolvedBuild;85:openedBuild,resolvedBuild;86:openedBuild,resolvedBuild') && e('主干,~~,主干,~~,主干,~~');            // 测试处理bug84 85 86的openedBuild resolvedBuild字段
