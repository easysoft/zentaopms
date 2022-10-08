#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getMenuModules();
cid=1
pid=1

测试查询 product 菜单的模块 >> product,branch,story
测试查询 my 菜单的模块 >> my,todo
测试查询 qa 菜单的模块 >> qa,bug,caselib
测试查询 project 菜单的模块 >> design,projectbuild,projectstory

*/

$group = new groupTest();

r($group->getMenuModulesTest('product')) && p('0,1,2') && e('product,branch,story');             // 测试查询 product 菜单的模块
r($group->getMenuModulesTest('my'))      && p('0,1')   && e('my,todo');                          // 测试查询 my 菜单的模块
r($group->getMenuModulesTest('qa'))      && p('0,1,6') && e('qa,bug,caselib');                   // 测试查询 qa 菜单的模块
r($group->getMenuModulesTest('project')) && p('0,3,4') && e('design,projectbuild,projectstory'); // 测试查询 project 菜单的模块