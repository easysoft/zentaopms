#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getPrivs();
cid=1
pid=1

测试查询分组 10     是否具有 action    模块 editComment 的权限 >> editComment
测试查询分组 10     是否具有 user      模块 bug         的权限 >> bug
测试查询分组 10     是否具有 trainplan 模块 view        的权限 >> view
测试查询分组 2      是否具有 trainplan 模块 create      的权限 >> create
测试查询分组 2      是否具有 todo      模块 activate    的权限 >> activate

*/

$group = new groupTest();

r($group->getPrivsTest(100)) && p('action:editComment') && e(0);             //测试查询不存在的分组是否具有 action    模块 editComment 的权限
r($group->getPrivsTest(10))  && p('action:editComment') && e('editComment'); //测试查询分组 10     是否具有 action    模块 editComment 的权限
r($group->getPrivsTest(10))  && p('user:bug')           && e('bug');         //测试查询分组 10     是否具有 user      模块 bug         的权限
r($group->getPrivsTest(10))  && p('trainplan:view')     && e('view');        //测试查询分组 10     是否具有 trainplan 模块 view        的权限
r($group->getPrivsTest(10))  && p('trainplan:create')   && e('');            //测试查询分组 10     是否具有 trainplan 模块 create      的权限
r($group->getPrivsTest(2))   && p('trainplan:create')   && e('create');      //测试查询分组 2      是否具有 trainplan 模块 create      的权限
r($group->getPrivsTest(2))   && p('todo:activate')      && e('activate');    //测试查询分组 2      是否具有 todo      模块 activate    的权限