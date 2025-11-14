#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getPrivsByModel();
timeout=0
cid=17841

- 传递空类型的情况 @0
- 传递错误的类型的情况 @0
- 获取敏捷项目的权限第bug条的delete属性 @deleteAction
- 获取瀑布项目的权限第story条的create属性 @create
- 获取看板项目的权限 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$projectTester = $tester->loadModel('project');

r($projectTester->getPrivsByModel(''))          && p()               && e('0');            // 传递空类型的情况
r($projectTester->getPrivsByModel('test'))      && p()               && e('0');            // 传递错误的类型的情况
r($projectTester->getPrivsByModel('scrum'))     && p('bug:delete')   && e('deleteAction'); // 获取敏捷项目的权限
r($projectTester->getPrivsByModel('waterfall')) && p('story:create') && e('create');       // 获取瀑布项目的权限
r($projectTester->getPrivsByModel('kanban'))    && p()               && e('0');            // 获取看板项目的权限
