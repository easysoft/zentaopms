#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getLinkList();
timeout=0
cid=0

- 查询关联的需求数量 @4
- 查询关联的需求列表
 - 第1条的title属性 @用户需求1
 - 第10条的title属性 @软件需求10
- 查询关联的任务列表
 - 第2条的name属性 @开发任务12
 - 第8条的name属性 @开发任务18
- 查询关联的Bug列表
 - 第3条的title属性 @BUG3
 - 第9条的title属性 @BUG9
- 查询关联的需求数量 @0
- 查询关联的需求数量 @0
- 查询关联的需求数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('story')->gen(10);
zdTable('task')->gen(10);
zdTable('bug')->gen(10);
zdTable('relation')->config('relation')->gen(10);
su('admin');

global $tester;
$mrModel = $tester->loadModel('mr');

/* MR id and product and type is right.  */
$linkedStories = $mrModel->getLinkList(1, 1, 'story');
r(count($linkedStories)) && p() && e('4'); // 查询关联的需求数量
r($linkedStories) && p('1:title;10:title') && e('用户需求1,软件需求10'); // 查询关联的需求列表

r($mrModel->getLinkList(1, 1, 'task')) && p('2:name;8:name') && e('开发任务12,开发任务18'); // 查询关联的任务列表
r($mrModel->getLinkList(1, 1, 'bug')) && p('3:title;9:title') && e('BUG3,BUG9'); // 查询关联的Bug列表

/* MR id is right, but product is wrong. */
r($mrModel->getLinkList(1, 2, 'story')) && p() && e('0'); // 查询关联的需求数量

/* MR id is wrong, but product is right. */
r($mrModel->getLinkList(2, 1, 'story')) && p() && e('0'); // 查询关联的需求数量

/* MR id and product is right, type is wrong. */
r($mrModel->getLinkList(1, 1, 'story1')) && p() && e('0'); // 查询关联的需求数量